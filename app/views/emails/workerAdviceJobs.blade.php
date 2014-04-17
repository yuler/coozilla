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
	Recommended List of your job opportunities 
</h6>
<table>
	<thead>
		<tr style="background:#eee;  ">
			<td style="font-weight:bold;text-align:center;">Job Title</td>
			<!-- <td>Publish By</td>
			<td>Contact Email</td> -->
			<td style="font-weight:bold;text-align:center;">Created Time</td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		@foreach ($jobs as $job)
		<style type="text/css">
		</style>
		<tr id="backColor">
			<td style="font-size:12px;">{{$job->job_title}}</td>
			<!-- <td>{{$job->hirer_orgname}}</td>
			<td>{{$job->member_email}}</td> -->
			<td style="width:120px;font-size:12px;">{{$job->created_at}}</td>
			<td style="width:40px;font-size:12px;"><a href="{{URL::to('/')}}/#!/job/detail/{{$job->id}}">Detail</a></td>
		</tr>
		@endforeach
	</tbody>
</table>
<hr style="clear: both; height: 0px; overflow: hidden; border-bottom-color: #ddd; border-bottom-width: 4px; background: transparent; margin: 15px 0; padding: 0; border-style: none none solid;" />
<p style="margin: 15px 0 0; padding: 0; border: 0;">
Contact Info&nbsp;:&nbsp;										
service@coozilla.com</p>

</body>
</html>
<!-- This document was created with MarkdownPad, the Markdown editor for Windows (http://markdownpad.com) -->
