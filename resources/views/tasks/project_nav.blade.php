@foreach($projects as $project)
<li class="p-menu__item list-group-item py-2 px-3 list-group-item-action {{ $current_project->id === $project->id ? 'list-group-item-info' : '' }}">
  <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="text-decoration-none">
    <span style="color:{{ $project->project_color }}">
    @if($current_project->id === $project->id)
    <i class="fas fa-folder-open" ></i>
    {{-- <i class="far fa-folder-open" aria-hidden="true"></i> --}}
    @else
    <i class="fas fa-folder mr-1" aria-hidden="true"></i>
    {{-- <i class="far fa-folder" aria-hidden="true"></i> --}}
    @endif
    </span>
    {{ $project->project_name ?? ''}}
    {{-- <span class="badge badge-pill badge-light float-right">{{ $project->category->category_name ?? '' }}</span> --}}

    {{-- ステータスと期限日はモデル側で書き換え --}}
    {{-- <span class="badge float-right {{ $project->category_class }}">{{ $project->category_name ?? ''}}</span> --}}
  </a>

  <a href="{{ route('projects.edit',['project_id'=>$project->id]) }}" class="small float-right">Edit</a>
</li>
@endforeach
