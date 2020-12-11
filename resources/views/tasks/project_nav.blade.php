@foreach($projects as $project)
<li class="p-menu__item list-group-item py-2 px-0 list-group-item-action {{ $current_project->id === $project->id ? 'list-group-item-info' : '' }}">
  <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="text-decoration-none text-dark">
    <div class="col-10 d-inline-block">
      <span style="color:{{ $project->project_color }}">
      @if($current_project->id === $project->id)
      <i class="fas fa-folder-open" ></i>
      @else
      <i class="fas fa-folder mr-1" aria-hidden="true"></i>
      @endif
      </span>
      {{ $project->project_name ?? ''}}
    </div>
  </a>

  <a href="{{ route('projects.edit',['project_id'=>$project->id]) }}" class="small float-right">
    <div class="col-2 d-inline-block">Edit</div>
  </a>
</li>
@endforeach
