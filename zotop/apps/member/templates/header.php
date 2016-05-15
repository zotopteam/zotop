{template 'header.php'}
<style type="text/css">
.user-sidebar{/*background-color:#fafafa;border:1px solid #f3f3f3;border-radius:4px;*/padding:10px;}
.user-sidebar .list-group{border-shadow:none;}
.user-sidebar .list-group-item{border-width: 1px 0;border-radius: 0;background:transparent;}
.user-sidebar .list-group-item:last-child{border:0;}

.list-sidebar dt{margin-top:20px;margin-bottom:8px;font-weight:700;font-size: 18px;}
.list-sidebar dt:first-child{margin-top:0;}
.list-sidebar dt a{text-decoration:none;color:#000;}
.list-sidebar dt.active a{color:#0072C6;}

.list-sidebar dd{padding:4px 0 4px 30px;}
.list-sidebar dd a{text-decoration:none;color:#666;}

</style>

<section class="section section-usercenter">	
	<div class="container">
	
		<div class="row">
			<div class="col-sm-2">
				<div class="user-sidebar">

					<dl class="list-sidebar">
						{loop member_hook::sidebar() $r}
						<dt class="item {if $r.active}active{/if}"><a href="{$r.href}"><i class="icon {$r.icon} fa-fw"></i> {$r.text}</a></dt>

						{if $r.menu and is_array($r.menu)}

							{loop $r.menu $m}
							<dd {if $m.active}class="active"{/if}>
								<a href="{$m.href}">{$m.text}</a>
								<span class="extra">{$m.extra}</span>
							</dd>
							{/loop}
						{/if}
						{/loop}
					</dl>
				</div>			
			</div> <!-- col -->
			<div class="col-sm-10">


