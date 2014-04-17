
<!-- Inliner Build Version 4380b7741bb759d6cb997545f3add21ad48f010b -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" style="margin: 0; padding: 0; border: 0;" xml:lang="en">
  <head>
    <title>MarkdownPad Document</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body style="font-family: Helvetica, arial, freesans, clean, sans-serif; font-size: 14px; line-height: 1.6; color: #333; max-width: 960px; background: #fff; margin: 0 auto; padding: 20px; border: 0;" bgcolor="#fff"><style type="text/css">
a:hover {
text-decoration: underline;
}
</style>
<h1 style="font-weight: bold; -webkit-font-smoothing: antialiased; font-size: 28px; color: #000; margin: 0 0 10px; padding: 0; border: 0;">Coozilla</h1>
<hr style="clear: both; height: 0px; overflow: hidden; border-bottom-color: #ddd; border-bottom-width: 4px; background: transparent; margin: 15px 0; padding: 0; border-style: none none solid;" /><h4 style="font-weight: bold; -webkit-font-smoothing: antialiased; font-size: 16px; margin: 20px 0 10px; padding: 0; border: 0;">Dear {{ $name }}</h4>
<h6 style="font-weight: bold; -webkit-font-smoothing: antialiased; color: #777; font-size: 14px; margin: 20px 0 10px; padding: 0; border: 0;">
	Candicates List of your published job ({{$job_title}})

</h6>
<div>
	@foreach ($logins as $login)
		<hr style="clear: both; height: 0px; overflow: hidden; border-bottom-color: #ddd; border-bottom-width: 1px; background: transparent; margin: 15px 0; padding: 0; border-style: none none solid;">
		<div style="font-weight:bold;font-size:20px;">{{$login->worker_name}}</div>
		<div>
			{{$login->worker_experience}}
		</div>
		<span style="font-size:12px;">Contact&nbsp;:&nbsp;<a href="mailto:{{$login->member_email}}">{{$login->member_email}}</a></span>
	@endforeach
</div>
<hr style="clear: both; height: 0px; overflow: hidden; border-bottom-color: #ddd; border-bottom-width: 4px; background: transparent; margin: 15px 0; padding: 0; border-style: none none solid;" />
<p style="margin: 15px 0 0; padding: 0; border: 0;">
Contact Info&nbsp;:&nbsp;	 										
service@coozilla.com</p>

</body>
</html>
<!-- This document was created with MarkdownPad, the Markdown editor for Windows (http://markdownpad.com) -->
