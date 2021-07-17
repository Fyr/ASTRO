<div class="page-header navbar navbar-fixed-top">
	<div class="page-header-inner ">
		<div class="page-logo">
			<a href="<?=$this->Html->url(array('controller' => 'Admin', 'action' => 'index'))?>">
				<!-- img src="/img/logo-white.png" alt="logo" class="logo-default" style="height: 30px; position: relative; top: -7px;" /-->
				<span class="logo-default"> CMS <?=Configure::read('domain.title')?> </span>
			</a>
			<div class="menu-toggler sidebar-toggler"> </div>
		</div>
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<!--li class="dropdown dropdown-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" class="img-circle" src="http://<?=Configure::read('domain.url')?>/assets/layouts/layout/img/avatar3_small.jpg" />
						<span class="username username-hide-on-mobile"> Admin </span>
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="<?=$this->Html->url(array('controller' => 'AdminUsers', 'action' => 'edit', 1))?>">
								<i class="icon-user"></i> <?=__('My profile')?>
							</a>
						</li>
						<li>
							<a href="javascript:;" onclick="setLang('eng')">
								<i class="icon-globe"></i> English version
							</a>
						</li>
						<li>
							<a href="javascript:;" onclick="setLang('rus')">
								<i class="icon-flag"></i> Русская версия
							</a>
						</li>
						<li>
							<a href="<?=$this->Html->url(array('controller' => 'AdminAuth', 'action' => 'logout'))?>">
								<i class="icon-key"></i> <?=__('Log out')?>
							</a>
						</li>
					</ul>
				</li-->
				<li class="dropdown" style="padding-top: 14px; margin: 0 20px; ">
					<span><?=__('Welcome, %s!', '<b>Admin</b>')?></span>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" href="<?=$this->Html->url(array('controller' => 'AdminAuth', 'action' => 'logout'))?>" title="<?=__('Logout')?>">
						<i class="icon-logout"></i>&nbsp;&nbsp;&nbsp;&nbsp;
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>