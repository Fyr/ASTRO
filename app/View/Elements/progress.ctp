<?
	$this->Html->script(array('/Core/js/json_handler', 'vendor/xdate'), array('inline' => false));
?>
<div class="text-center">
	<br />
	<span id="task"><?=Hash::get($task, 'task_name')?></span><br />
	<br />
<?
	if (Hash::get($task, 'subtask')) {
?>
	<div id="progressSubtask">
		<span class="info">&nbsp;</span>
		<div class="progress progress-striped">
			<div class="progress-bar progress-bar-success" role="progressbar"></div>
		</div>
	</div>
<?
	}
?>
	<div id="progressTotal">
		<span class="info">&nbsp;</span>
		<div class="progress progress-striped">
			<div class="progress-bar progress-bar-info" role="progressbar"></div>
		</div>
	</div>

	<div style="height: 30px;">
		<button id="taskAbort" class="btn btn-danger">Прервать</button>
		<button id="taskDone" class="btn btn-info" style="display: none;" onclick="window.location.reload()">OK</button>
		<button id="taskAborted" class="btn" style="display: none;" onclick="window.location.reload()">Отмена</button>
	</div>
</div>
<script>
var ABORT, HANGS;

function setTitle(msg) {
	$('#task').html(msg);
}

function setProgress(task, context) {
	context = (context) ? context + ' ' : '';
	var aStats = [
		'<span>' + (task.subtask ? 'Всего: ' : 'Прогресс: ') + '</span>' +  task.progress.percent + '% (' + task.progress.progress + '/' + task.progress.total + ')',
		'<span>' + 'Время: ' + '</span>' + Date.getPeriod(task.progress.exec_time, 'rus'),
		'<span>' + 'Осталось: '  + '</span>' + '~' + Date.getPeriod(task.progress.time_finish, 'rus')
	];
	if (!task.subtask) {
		aStats.push('<span>' + 'Скорость: ' + '</span>' + task.progress.avg_speed + '/сек');
	}
	$(context + '.info').html(aStats.join('&nbsp;&nbsp;'));
	$(context + '.progress-bar').css('width', task.progress.percent + '%');
}

function renderStatus(task) {
	var ifHangs = (task.progress.hangs) ? ' ' + HANGS : '';
	setTitle(task.status == '<?=Task::ABORT?>' ? ABORT : getTaskName(task.task_name) + ifHangs);

	if (task.subtask) {
		setTitle(getTaskName(task.subtask.task_name) + ifHangs);
		if (task.subtask.progress) {
			setProgress(task.subtask, '#progressSubtask');
			if (task.subtask.progress.time_finish > task.progress.time_finish) {
				task.progress.time_finish = task.subtask.progress.time_finish;
			}
		}
	}
	setProgress(task, '#progressTotal');
}

function updateStatus(url) {
	$.get(url, null, function(response){
		if (checkJson(response)) {
			renderStatus(response.data);
			if (response.data.status == '<?=Task::CREATED?>' || response.data.status == '<?=Task::RUN?>') {
				setTimeout(function() { updateStatus(url) }, 1000);
			} else {
				$('#taskAbort').hide();
				if (response.data.status == '<?=Task::DONE?>') {
					setTitle('Процесс выполнен успешно');
					$('#taskDone').show();
				} else if (response.data.status == '<?=Task::ABORT?>') {
					setTitle(ABORT);
					setTimeout(function() { updateStatus(url) }, 1000);
				} else if (response.data.status == '<?=Task::ABORTED?>') {
					setTitle('Процесс остановлен пользователем!');
					$('#taskAborted').show();
				} else if (response.data.status == '<?=Task::ERROR?>') {
					setTitle('<span class="err-msg">Ошибка выполнения процесса! ' + response.data.xdata + '</span>');
					$('#taskAborted').show();
				}
			}
		}
	});
	return false;
}

function getTaskName(taskName) {
	var aTaskNames = null;
<?
	if (isset($aTaskNames)) {
?>
	aTaskNames = <?=json_encode($aTaskNames)?>;
<?
	}
?>
	return (aTaskNames && aTaskNames[taskName]) ? aTaskNames[taskName] : taskName;
}

$(function(){
	ABORT = 'Остановка процесса...';
	HANGS = '(не отвечает)';
	renderStatus(<?=json_encode($task)?>);
	updateStatus('<?=$this->Html->url(array('controller' => 'AdminAjax', 'action' => 'getTaskStatus', Hash::get($task, 'id')))?>.json');
	$('#taskAbort').click(function(){
		setTitle(ABORT);
		$.get('<?=$this->Html->url(array('controller' => 'AdminAjax', 'action' => 'taskAbort', Hash::get($task, 'id')))?>.json', null, function(response){
			if (checkJson(response)) {
			}
		});
	});
});
</script>