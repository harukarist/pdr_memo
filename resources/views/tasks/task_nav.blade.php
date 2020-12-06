<div class="btn-group" role="group" aria-label="task-status">
  <a href="{{ route('tasks.index',['project_id' => $current_project->id]) }}" class="btn {{ $has_done ? '' : 'btn-secondary' }}">
    <i class="far fa-circle mr-1" aria-hidden="true"></i>未完了タスク</a>
  <a href="{{ route('tasks.done',['project_id'=>$current_project->id])  }}" class="btn {{ $has_done ? 'btn-secondary' : '' }}">
    <i class="far fa-check-circle mr-1" aria-hidden="true"></i>完了済み</a>
</div>
