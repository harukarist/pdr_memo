<div class="row mb-2">
  <div class="btn-group col" role="group" aria-label="task-status">
    <a href="{{ route('tasks.index',['project_id' => $current_project->id]) }}" class="btn {{ $active_status == 'UNDONE' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="far fa-circle mr-1" aria-hidden="true"></i>未完了タスク</a>
    <a href="{{ route('tasks.done',['project_id'=>$current_project->id])  }}" class="btn {{ $active_status == 'DONE' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="far fa-check-circle mr-1" aria-hidden="true"></i>完了済みタスク</a>
    <a href="{{ route('tasks.all',['project_id'=>$current_project->id])  }}" class="btn {{ $active_status == 'ALL' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="fas fa-list-ul mr-1" aria-hidden="true"></i>全て</a>
  </div>
  <div class="col">
    <form action="{{ route('tasks.search',['project_id' => $current_project->id ]) }}" method="GET" class="form-inline">
    <div class="form-group">
      <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Search...">
    </div>
    <input type="submit" value="検索" class="btn btn-primary">
    </form>
  </div>

</div>
