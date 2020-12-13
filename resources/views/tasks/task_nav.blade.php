<div class="d-flex flex-column flex-md-row mb-2 justify-content-between">
  <div class="btn-group mr-2" role="group" aria-label="task-status">
    <a href="{{ route('tasks.index',['project_id' => $current_project->id]) }}" class="btn {{ $active_status == 'UNDONE' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="far fa-circle mr-1" aria-hidden="true"></i>未完了</a>
    <a href="{{ route('tasks.done',['project_id'=>$current_project->id])  }}" class="btn {{ $active_status == 'DONE' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="far fa-check-circle mr-1" aria-hidden="true"></i>完了済み</a>
    <a href="{{ route('tasks.all',['project_id'=>$current_project->id])  }}" class="btn {{ $active_status == 'ALL' ? 'btn-secondary' : 'btn-outline-secondary' }}">
      <i class="fas fa-list-ul mr-1" aria-hidden="true"></i>全て</a>
  </div>
  <div class="d-flex">
    <form action="{{ route('tasks.search',['project_id' => $current_project->id ]) }}" method="GET" class="form-inline">
    <div class="form-group m-0">
      <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="タスクをキーワード検索">
    </div>
    <input type="submit" value="検索" class="btn btn-primary">
    </form>
  </div>

</div>
