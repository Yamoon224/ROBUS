<?php

namespace App\Http\Livewire;

use App\Http\Services\ServiceAvailabilityService;
use App\Models\ServiceCloseSchedule;
use App\Models\ServiceCloseSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CloseServiceSchedule extends Component
{
    public $service;
    public $time_begin;
    public $time_end;
    public $appStatus;
    public $robusId;
    public $robus_days;
    public $description;
    public $manual_message;

    protected $rules = [
        'time_end' => 'required',
        'description' => 'required',
        'time_begin' => 'required',
    ];

    protected $messages = [
        'time_end.required' => 'Ce champ est requis',
        'description.required' => 'Ce champ est requis',
        'time_begin.required' => 'Ce champ est requis',
    ];

    public function mount($service)
    {
        abort_unless(array_key_exists($service, ServiceAvailabilityService::SERVICES), 404);

        $this->service = $service;
        $this->manual_message = $this->setting()->manual_message;
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::guard('web')->user()->hasPermissionTo('open-robus'), 403);
    }

    private function setting(): ServiceCloseSetting
    {
        return ServiceCloseSetting::firstOrCreate(['service' => $this->service]);
    }

    private function schedule($id): ServiceCloseSchedule
    {
        return ServiceCloseSchedule::where('service', $this->service)->findOrFail($id);
    }

    public function edit($id, $state)
    {
        $this->authorizeAdmin();

        $check = $this->schedule($id);
        $this->robusId = $check->id;
        $this->robus_days = $check->day_of_week;
        $this->time_begin = substr($check->time_start, 0, 5);
        $this->time_end = substr($check->time_end, 0, 5);
        $this->appStatus = $check->status ? 1 : 0;
        $this->description = $check->description;
        $this->emit($state);
    }

    public function updateApp()
    {
        $this->authorizeAdmin();
        $this->validate();

        $this->schedule($this->robusId)->update([
            'time_end' => $this->time_end,
            'time_start' => $this->time_begin,
            'description' => $this->description,
            'status' => true,
            'created_by' => sabdy()->id,
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Plage horaire modifiée !');
    }

    public function resetField()
    {
        $this->description = '';
        $this->time_end = '';
        $this->time_begin = '';
        $this->emit('modal-hide', 'hide');
    }

    // Active / désactive la règle horaire d'un jour
    public function disable()
    {
        $this->authorizeAdmin();

        $check = $this->schedule($this->robusId);
        $check->update(['status' => !$check->status]);

        $this->emit('modal-hide', 'hide');
        $this->emit('success', $check->status ? 'Règle horaire activée !' : 'Règle horaire désactivée !');
    }

    // Activation / désactivation manuelle : prime sur les horaires
    public function setManualMode($mode)
    {
        $this->authorizeAdmin();

        $mode = in_array($mode, [ServiceAvailabilityService::MODE_OPEN, ServiceAvailabilityService::MODE_CLOSED], true)
            ? $mode : null;

        $message = $mode === ServiceAvailabilityService::MODE_CLOSED ? (trim($this->manual_message) ?: null) : null;

        $this->setting()->update([
            'manual_mode' => $mode,
            'manual_message' => $message,
            'updated_by' => sabdy()->id,
        ]);

        $this->manual_message = $message;

        $this->emit('success', [
            ServiceAvailabilityService::MODE_OPEN => 'Service activé manuellement !',
            ServiceAvailabilityService::MODE_CLOSED => 'Service désactivé manuellement !',
        ][$mode] ?? 'Retour au mode automatique (horaires) !');
    }

    public function render()
    {
        return view('livewire.close-service-schedule', [
            'closeapps' => ServiceCloseSchedule::where('service', $this->service)->orderBy('day_number')->get(),
            'availability' => ServiceAvailabilityService::status($this->service),
            'serviceLabel' => ServiceAvailabilityService::SERVICES[$this->service],
        ]);
    }
}
