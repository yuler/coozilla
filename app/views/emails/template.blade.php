<div>
	<pre>
Dear  {{ $name }},
Welcome to coozilla !!! 
This is an activation email,Please do not reply.
<a href="{{URL::to('/')}}/member/activeAccount/{{$key}}">Click here to activate your account</a>
													Coozilla
													{{ date("Y.m.d") }}
	</pre>
</div>