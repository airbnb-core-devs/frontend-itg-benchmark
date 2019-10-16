const drawer = mdc.drawer.MDCDrawer.attachTo(document.querySelector('.mdc-drawer'));
const list = mdc.list.MDCList.attachTo(document.querySelector('.mdc-list'))
const topAppBar = mdc.topAppBar.MDCTopAppBar.attachTo(document.querySelector('.mdc-top-app-bar'));
const buttons = document.querySelectorAll('.mdc-button');
const textsFields = document.querySelectorAll('.mdc-text-field');
const snackbars = document.querySelectorAll('.mdc-snackbar');

snackbars.forEach(snackbar => {
  mdc.snackbar.MDCSnackbar.attachTo(snackbar);
});


textsFields.forEach(textField => {
  mdc.textField.MDCTextField.attachTo(textField);
});
buttons.forEach(button => {
  mdc.ripple.MDCRipple.attachTo(button);
});
topAppBar.setScrollTarget(document.getElementById('main-content'));
topAppBar.listen('MDCTopAppBar:nav', () => {
  drawer.open = !drawer.open;
});

$(document).ready(function(){
  $(window).scroll(function(){
    var scroll = $(window).scrollTop();
    if ($('#topAppBar').hasClass('mdc-top-app-bar--short')) {
      closeNav(scroll)
    }
    if ($('#topAppBar').hasClass('mdc-top-app-bar--short-collapsed')) {
      $('#topAppBar').css('width', '125px');
    } else {
      $('#topAppBar').css('width', '100%');
    }

    $('#sidebarMenu').removeClass('mdc-drawer--open');
  });
  
  function closeNav(scroll) {
    if (scroll > 80) {
      $('#topAppBar').addClass('mdc-top-app-bar--short-collapsed');
      
    } else {
      $('#topAppBar').removeClass('mdc-top-app-bar--short-collapsed');
    } 
  }
  
  var windowWidth = $(window).width();
  sessionStorage.setItem('windowWidth', windowWidth); 

  if ((windowWidth >= 576 && windowWidth < 992) || (windowWidth < 576)) {
    $('#sidebarMenu, #sidebarMenuButton').removeClass('d-none');
    $('#dropdown').addClass('d-none');
    $('#topAppBar2').addClass('d-none');
    $('#topAppBar').removeClass('mdc-top-app-bar--fixed');
    $('#topAppBar').addClass('mdc-top-app-bar--short');
    $('#topAppBar').css('z-index', '0');
    $('#topAppBar .mdc-top-app-bar__section--align-end').css('margin-right', '0');
    $('#cartButton').css('margin-right', '5px');

  }
  if ( windowWidth >= 992 ) {
    $('#sidebarMenu, #sidebarMenuButton').addClass('d-none');
    $('#sidebarMenu').removeClass('mdc-drawer--open');
    $('#dropdown').removeClass('d-none');
    $('#topAppBar2').removeClass('d-none');
    $('#topAppBar').removeClass('mdc-top-app-bar--short mdc-top-app-bar--short-collapsed');
    $('#topAppBar').addClass('mdc-top-app-bar--fixed');
    $('#topAppBar .mdc-top-app-bar__section--align-end').css('margin-right', '5%');
  }
  
  $(window).resize(function(){
    var windowWidth = $(window).width();

    if ((windowWidth >= 576 && windowWidth < 992) || (windowWidth < 576)) {
      $('#sidebarMenu, #sidebarMenuButton').removeClass('d-none');
      $('#dropdown').addClass('d-none');
      $('#topAppBar').removeClass('mdc-top-app-bar--fixed');
      $('#topAppBar2').addClass('d-none');
      $('#topAppBar').addClass('mdc-top-app-bar--short');
      $('#topAppBar .mdc-top-app-bar__section--align-end').css('margin-right', '0');
    }
    if ( windowWidth >= 992 ) {
      $('#sidebarMenu, #sidebarMenuButton').addClass('d-none');
      $('#sidebarMenu').removeClass('mdc-drawer--open');
      $('#dropdown').removeClass('d-none'); 
      $('#topAppBar2').removeClass('d-none');
      $('#topAppBar').removeClass('mdc-top-app-bar--short mdc-top-app-bar--short-collapsed');
      $('#topAppBar').addClass('mdc-top-app-bar--fixed');
      $('#topAppBar .mdc-top-app-bar__section--align-end').css('margin-right', '5%');
    }
  });

  $('.actionButton').click(function(){
    
    var route = $(this).data('href');
    window.location.href = route;
  });
});
$.validator.setDefaults({
  errorClass: 'label-invalid mb-0',
  highlight: function (element) {
    $(element).removeClass('input-valid');
    $(element).addClass('input-invalid');
  },
  unhighlight: function (element) {
    $(element).removeClass('input-invalid');
    $(element).addClass('input-valid');
  }
});
$('#loginDropdownForm').validate({
  rules: {
    email: {
      required: true,
      email: true
    },
    password: {
      required: true
    }
  },
  messages: {
    email: {
      required: 'Esse campo é obrigatório',
      email: 'Esse não é um endereço de email válido.'
    },
    password: {
      required: 'Esse campo é obrigatório.'
    }
  }
});