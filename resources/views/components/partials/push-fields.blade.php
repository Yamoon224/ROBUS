  @include('components.transaction-phone-input', [
      'id' => 'phone_inter_out_civ',
      'model' => 'phone_inter_out_civ',
      'label' => 'Numero de teléphone',
      'error' => 'phone_inter_out_civ',
  ])

  @include('components.transaction-text-input', [
      'id' => 'name_custumer',
      'model' => 'name_custumer',
      'label' => 'Nom et Prénom',
      'error' => 'name_custumer',
  ])
