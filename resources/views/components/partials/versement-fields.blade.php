@include('components.transaction-versement-input', [
    'id' => 'num_accompte',
    'model' => 'num_accompte',
    'label' => 'Numero du compte',
    'error' => 'num_accompte',
])

@include('components.transaction-versement-input', [
    'id' => 'bank_code',
    'model' => 'bank_code',
    'label' => 'Code banque',
    'error' => 'bank_code',
])
@include('components.transaction-versement-input', [
    'id' => 'guichet_code',
    'model' => 'guichet_code',
    'label' => 'Code Guichet',
    'error' => 'guichet_code',
])

@include('components.transaction-versementCle-input', [
    'id' => 'num_authers',
    'model' => 'num_authers',
    'label' => 'CLE RIB',
    'error' => 'num_authers',
])
