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
    $('#imageCaptureId').attr(
      'src',
      window.URL.createObjectURL(uploader.files[0]),
    )
  }
}

function fasterPreviewForm(uploader) {
  if (uploader.files && uploader.files[0]) {
    $('#imageCaptureForm').attr(
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
