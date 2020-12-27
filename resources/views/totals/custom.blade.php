@extends('layouts.app')

@section('content')
<div class="container c-container">
  <h5 class="mb-4">合計時間の編集</h5>
  <div class="col">
    <form action="{{ route('totals.custom') }}" method="post">
      @csrf
      {{-- バリデーションエラー --}}
      @if($errors->any())
      <div class="alert alert-danger">
        <ul class="m-0">
          @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
          @endforeach
        </ul>
      </div>
      @endif

    <div class="card">
      <div class="card-header">カテゴリー別</div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <td>
                カテゴリー名
              </td>
              <td>
                記録の合計時間
              </td>
              <td>
                記録に追加する合計時間
              </td>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $category)
              <tr class="form-group">
                <td>
                  <label for="category_hours">
                  {{ $category->category_name }}
                  </label>
                </td>
                <td>
                  @if(isset($summaries['categories'][$category->category_name]))
                  {{ $summaries['categories'][$category->category_name] ?? 0}}h
                  @else
                  0h
                  @endif
                </td>
                <td>
                  <div class="form-inline">
                    <input type="tel" class="form-control @error('category_hours'.$category->id) is-invalid @enderror" name="category_hours[{{ $category->id }}]" id="category_hours[{{ $category->id }}]" value="{{ old('category_hours'.$category->id) ?? $category->custom_hours }}" />
                    <span class="ml-2">h</span>
                  </div>
                <td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">プロジェクト別</div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <td>
                プロジェクト名
              </td>
              <td>
                記録の合計時間
              </td>
              <td>
                記録に追加する合計時間
              </td>
            </tr>
          </thead>
          <tbody>
            @foreach($projects as $project)
              <tr class="form-group">
                <td>
                  <label for="project_hours">
                  {{ $project->project_name }}
                  </label>
                </td>
                <td>
                  @if(isset($summaries['projects'][$project->project_name]))
                  {{ $summaries['projects'][$project->project_name] ?? 0}}h
                  @else
                  0h
                  @endif
                </td>
                <td>
                  <div class="form-inline">
                    <input type="tel" class="form-control @error('project_hours'.$project->id) is-invalid @enderror" name="project_hours[{{ $project->id }}]" id="project_hours[{{ $project->id }}]" value="{{ old('project_hours'.$project->id) ?? $project->custom_hours }}" />
                    <span class="ml-2">h</span>
                  </div>
                <td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">変更</button>
    </form>

  </div>
</div>
@endsection
