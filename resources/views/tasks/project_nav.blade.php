@foreach($projects as $project)
<li class="p-menu__item list-group-item p-2 list-group-item-action {{ $current_project->id === $project->id ? 'list-group-item-info' : '' }}">
  <div class="d-flex flex-column">
    <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="text-decoration-none text-dark">
      {{-- <div class="col-10 d-inline-block"> --}}
        <span style="color:{{ $project->project_color }}">
        @if($current_project->id === $project->id)
        <i class="fas fa-folder-open" aria-hidden="true"></i>
        @else
        <i class="fas fa-folder" aria-hidden="true"></i>
        @endif
        </span>
        {{ $project->project_name ?? ''}}
      {{-- </div> --}}
    </a>

    <div class="small text-right mr-2">
      <a href="{{ route('projects.edit',['project_id'=>$project->id]) }}">
        <i class="p-task__icon fas fa-pencil-alt" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</li>
@endforeach
