@if(config('custom.PKG_DEV'))
    <?php $faq_pkg_prefix = '/packages/abs/faq-pkg/src';?>
@else
    <?php $faq_pkg_prefix = '';?>
@endif

<script type="text/javascript">
    var faq_list_template_url = "{{asset($faq_pkg_prefix.'/public/themes/'.$theme.'/faq-pkg/faq/list.html')}}";
    var faq_form_template_url = "{{asset($faq_pkg_prefix.'/public/themes/'.$theme.'/faq-pkg/faq/form.html')}}";
</script>
<script type="text/javascript" src="{{asset($faq_pkg_prefix.'/public/themes/'.$theme.'/faq-pkg/faq/controller.js')}}"></script>
