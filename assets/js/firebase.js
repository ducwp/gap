// Initialize Firebase
firebase.initializeApp(GAPfirebaseConfig);
firebase.analytics();

window.onload = function () {
  render();
};

function render() {
  window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
  recaptchaVerifier.render();
}

function phoneAuth() {
  //get the number
  var form_field_phone = document.getElementById('form-field-phone');
  var number = form_field_phone.value;
  if (!Geekcodelab_isTel(number)) {
    alert("Vui lòng nhập một số điện thoại hợp lệ.");
    form_field_phone.focus();
    return;
  }
  number = '+84' + number.substring(1);
  // alert(number);
  var verificationId = document.getElementById('form-field-verificationId');
  //it takes two parameter first one is number and second one is recaptcha
  firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
    //s is in lowercase
    window.confirmationResult = confirmationResult;
    coderesult = confirmationResult;
    console.log(coderesult);
    alert("Đã gửi mã xác minh OTP");
    verificationId.value = confirmationResult.verificationId;

  }).catch(function (error) {
    alert(error.message);
  });
}

function codeverify() {
  var code = document.getElementById('verificationCode').value;

  coderesult.confirm(code).then(function (result) {
    alert("Successfully registered");
    var user = result.user;
    console.log(user);
  }).catch(function (error) {
    alert(error.message);
  });
}

function Geekcodelab_isTel(tel) {
  //var regex = /([\+84|84|0]+(3|5|7|8|9|1[2|6|8|9]))+([0-9]{8})\b/g;
  var regex = /^(0[35789]{1})+([0-9]{8})+$/g;
  return regex.test(tel);
}