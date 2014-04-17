
<!-- Layout
    ================================================== -->
<script type="text/javascript">
var contextPath="{{URL::to('/')}}";
</script>
{{ HTML::script('assets/js/lib/jquery-1.8.3.min.js') }}
{{ HTML::script('assets/js/lib/jquery.form.js') }}

{{ HTML::script('assets/js/lib/bootstrap.min.js') }}
{{ HTML::style('assets/css/lib/bootstrap.min.css') }}

{{ HTML::script('assets/js/lib/angular.min.js') }}
{{ HTML::script('assets/js/lib/angular-route.min.js') }}
{{ HTML::script('assets/js/lib/angular-resource.min.js') }}

{{ HTML::script('assets/js/app/app.js') }}
{{ HTML::script('assets/js/app/controllers.js') }}
{{ HTML::script('assets/js/app/services.js') }}
{{ HTML::script('assets/js/app/directives.js') }}
{{ HTML::script('assets/js/app/filters.js') }}
<!-- global js -->
{{ HTML::script('assets/js/common/global.js') }}

<!-- thirdparty-->

<!--[if IE 7]>
<link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
<![endif]-->
{{ HTML::style('thirdparty/FontAwesome/css/font-awesome.min.css') }}

<!-- select -->
{{ HTML::style('thirdparty/BootstrapSelect/bootstrap-select.min.css') }}
{{ HTML::script('thirdparty/BootstrapSelect/bootstrap-select.min.js') }}
<!-- 
{{ HTML::style('thirdparty/select2-3.4.5/select2.css') }}
{{ HTML::script('thirdparty/select2-3.4.5/select2.js') }} 
-->

<!-- editor -->
{{ HTML::script('thirdparty/bootstrap-wysiwyg-master/bootstrap-wysiwyg-master/bootstrap-wysiwyg.js') }}
{{ HTML::script('thirdparty/jquery.hotkeys-master/jquery.hotkeys-master/jquery.hotkeys.js') }}
<!-- {{ HTML::script('assets/js/bootstrap-wysihtml5.js') }}
{{ HTML::style('assets/css/bootstrap-wysiHtml5.css') }}
 -->

 <!--uploadify-->
{{ HTML::script('thirdparty/uploadify/jquery.uploadify.min.js') }}
{{ HTML::style('thirdparty/uploadify/uploadify.css') }}

<!-- pin-->
{{ HTML::script('thirdparty/jquery.pin-gh-pages/jquery.pin.min.js') }}
<!-- stick up -->
<!-- {{ HTML::script('thirdparty/stickUp-master/stickUp.min.js') }} -->

<!-- switch -->
{{ HTML::script('thirdparty/bootstrap-switch-master/build/js/bootstrap-switch.min.js') }}
{{ HTML::style('thirdparty/bootstrap-switch-master/build/css/bootstrap3/bootstrap-switch.min.css') }}

<!-- unslider -->
<!-- {{ HTML::script('thirdparty/unslider-master/src/unslider.min.js') }} -->

<!--icheck-->
{{ HTML::script('thirdparty/iCheck-master/icheck.min.js') }}
{{ HTML::style('thirdparty/iCheck-master/skins/all.css') }}

<!-- grumble -->
{{ HTML::script('thirdparty/grumble.js-master/js/jquery.grumble.min.js') }}
{{ HTML::style('thirdparty/grumble.js-master/css/grumble.min.css') }}

<!-- buttons -->
{{ HTML::script('assets/Buttons-master/js/buttons.js') }}
{{ HTML::style('assets/Buttons-master/css/buttons.css') }}

<!-- summernote -->
{{ HTML::script('thirdparty/summernote/summernote.min.js') }}
{{ HTML::style('thirdparty/summernote/summernote.css') }}

<!--app main css-->
{{ HTML::style('assets/css/main.css') }}
