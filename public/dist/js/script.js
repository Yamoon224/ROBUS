$('#profileImage').click(function (e) {
  $('#imageUpload').click()
})

$('#imageCaptureId').click(function (e) {
  $('#imageUploadId').click()
})

$('#imageCaptureForm').click(function (e) {
  $('#imageUploadForm').click()
})


function fasterPreview(uploader) {
  if (uploader.files && uploader.files[0]) {
    $('#profileImage').attr(
      'src',
      window.URL.createObjectURL(uploader.files[0]),
    )
  }
}

function fasterPreviewId(uploader) {
  if (uploader.files && uploader.files[0]) {
    $('#imageUploadId').attr(
      'src',
      window.URL.createObjectURL(uploader.files[0]),
    )
  }
}

function fasterPreviewForm(uploader) {
  if (uploader.files && uploader.files[0]) {
    $('#imageUploadForm').attr(
      'src',
      window.URL.createObjectURL(uploader.files[0]),
    )
  }
}

$('#imageUpload').change(function () {
  fasterPreview(this)
})

$('#imageUploadId').change(function () {
  fasterPreviewId(this)
})

$('#imageUploadForm').change(function () {
  fasterPreviewForm(this)
})

function close_give() {
  $('#create-provider-modal').removeClass('overflow-y-auto show')
}
var phone = document.querySelector('#phone')

var utils =
  document.getElementById('app_url').value +
  '/vendors/intl-tel-input/build/js/utils.js'

if (phone) {
  var phone_intl = window.intlTelInput(phone, {
    hiddenInput: 'full_phone',
    separateDialCode: true,
    utilsScript: utils,
    preferredCountries: ['ci'],
    initialCountry: 'auto',
    localizedCountries: {
      fr: 'French',
    },
    geoIpLookup: function (callback) {
      $.get(
        'https://ipinfo.io?token=6714a214226e98',
        function () {},
        'jsonp',
      ).always(function (resp) {
        var countryCode = resp && resp.country ? resp.country : ''
        callback(countryCode)
      })
    },
  })

  phone.addEventListener('countrychange', function () {
    var country = phone_intl.getSelectedCountryData()
  })

  phone.addEventListener('change', function () {
    phoneValidate('phone', phone_intl)
  })
}

function phoneValidate(inputId, iti) {
  console.log(inputId)
  var input = document.querySelector('#' + inputId),
    errorMsg = document.querySelector('#invalid_' + inputId),
    validMsg = document.querySelector('#valid_' + inputId)

  // here, the index maps to the error code returned from getValidationError - see readme
  var errorMap = [
    'Numéro invalide',
    'Code pays invalide',
    'Numéro trop court',
    'Numéro trop long',
    'Numéro invalide',
  ]

  var reset = function () {
    input.classList.remove('error')
    errorMsg.innerHTML = ''
    errorMsg.classList.add('hide')
    validMsg.classList.add('hide')
  }

  var country = iti.getSelectedCountryData()
  console.log(country.dialCode)

  input.addEventListener('blur', function () {
    reset()
    if (input.value.trim()) {
      const button = document.getElementById('go')
      if (iti.isValidNumber()) {
        validMsg.classList.remove('hide')
        button.disabled = false
      } else {
        input.classList.add('error')
        var errorCode = iti.getValidationError()
        errorMsg.innerHTML = errorMap[errorCode]
        errorMsg.classList.remove('hide')
        //disabled button
        button.disabled = true
      }
    }
  })

  // on keyup / change flag: reset
  input.addEventListener('change', reset)
  input.addEventListener('keyup', reset)
}

// var start_date = document.getElementById('start_date')
// if (start_date) {
//   start_date.max = new Date().toISOString().split('T')[0]
// }

// var end_date = document.getElementById('end_date')
// if (end_date) {
//   end_date.max = new Date().toISOString().split('T')[0]
// }

// var $table = $('#default-table')

// $(function () {
//   $('#toolbar')
//     .find('select')
//     .change(function () {
//       $table.bootstrapTable('destroy').bootstrapTable({
//         exportDataType: $(this).val(),
//         exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
//         columns: [
//           {
//             field: 'state',
//             checkbox: true,
//             visible: $(this).val() === 'selected',
//           },
//         ],
//       })
//     })
//     .trigger('change')
// })


// function promptAlert(d) {
//   event.preventDefault()
//   const url = d.getAttribute('href')
//   const message = d.getAttribute('data-message')

//   Swal.fire({
//     title: 'Confirmation',
//     text: message,
//     icon: 'warning',
//     confirmButtonText: 'Oui, Confirmer',
//     cancelButtonText: 'Non, Annuler',
//     showCloseButton: true,
//     showCancelButton: true,
//     reverseButtons: true,
//   }).then(function (result) {
//     if (result.value) {
//       window.location.href = url
//     }
//   })
// }

// function loadingTemplate() {
//   return '<i class="fa fa-spinner fa-spin fa-fw fa-2x"></i>'
// }

function generatePassword() {
  var chars =
    '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  var passwordLength = 12
  var password = ''
  for (var i = 0; i <= passwordLength; i++) {
    var randomNumber = Math.floor(Math.random() * chars.length)
    password += chars.substring(randomNumber, randomNumber + 1)
  }
  document.getElementById('password').value = password
  document.getElementById('password_confirmation').value = password
}
