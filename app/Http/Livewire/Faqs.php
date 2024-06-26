<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use Livewire\Component;
use Livewire\WithPagination;

class Faqs extends Component
{

    use WithPagination;
    public $search = "";
    public $faqId;
    public $faqStatus;
    public $question;
    public $answer;

    protected $rules = [
        'question' => 'required',
        'answer' => 'required',
    ];

    protected $messages = [
        'question.required' => 'Ce champ est requis',
        'answer.required' => 'Le montant est requis',
    ];


    public function add()
    {
        $this->validate();
        $check = Faq::whereId($this->faqId)->first();
        if (!$check) {
            Faq::create(
                [
                    'question' => $this->question,
                    'answer'  => $this->answer,
                    'status'  => 1
                ]
            );
            $this->resetField();
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Question ajouté !');
        } else $this->emit('error', 'Question  existante !');
    }

    public function edit($id, $state)
    {
        $check = Faq::whereId($id)->first();
        $this->faqId = $check->id;
        $this->question = $check->question;
        $this->answer = $check->answer;
        $this->faqStatus = $check->status;
        $this->emit($state);
    }

    public function update()
    {
        $this->validate();
        $check = Faq::whereId($this->faqId)->first();
        $check->update([
            'question' => $this->question,
            'answer'  => $this->answer,
            'status'  => 1
        ]);

        $this->resetField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Question modifié !');
    }

    public function resetField()
    {
        $this->question = '';
        $this->answer = '';
        $this->emit('modal-hide', 'hide');
    }

    public function disable()
    {
        $check = Faq::find($this->faqId);
        if (!empty($check)) {
            if ($check->status == 0) $check->update(['status' => 1]);
            else $check->update(['status' => 0]);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', $check->status == 1 ? 'faq activée !' : 'faq désactivée !');
        }
    }

    public function delete()
    {
        $check = Faq::find($this->faqId);
        if (!empty($check)) {
            $check->destroy($this->faqId);
            $this->emit('modal-hide', 'hide');
            $this->emit('success', 'Faq supprimée !');
        }
    }

    public function render()
    {
        $faqs = Faq::query();

        if ($this->search != "") {

            $this->resetPage();

            $faqs->where("question", "like",  "%" . $this->search . "%");
        }
        return view('livewire.faqs', [
            "faqs" => $faqs->orderBy('question', 'desc')->latest()->paginate(10),
        ]);
    }
}