@extends('layouts.teacher')

@section('title', 'Exam Grading')
@section('page-title', 'Exam Grading')

@section('content')
<style>
    .page-shell { display: grid; gap: 18px; }
    .panel { background: #fff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05); }
    .hero { background: linear-gradient(135deg, #0f172a 0%, #111827 100%); color: #fff; border-color: rgba(255,255,255,0.08); }
    .hero h1 { margin: 0; font-size: 30px; }
    .hero p { margin: 8px 0 0; color: #cbd5e1; }
    .crumbs { margin-top: 12px; font-size: 14px; color: #bfdbfe; }
    .crumbs a { color: #dbeafe; text-decoration: none; }
    .filters { display: grid; grid-template-columns: 1.4fr .8fr .8fr auto; gap: 12px; align-items: end; }
    @media (max-width: 900px) { .filters { grid-template-columns: 1fr; } }
    label { display: block; margin-bottom: 6px; color: #334155; font-size: 13px; font-weight: 700; }
    input, select { width: 100%; border: 1px solid #dbe3ee; border-radius: 10px; padding: 11px 12px; font-size: 14px; color: #0f172a; background: #fff; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 11px 16px; border-radius: 10px; border: 0; text-decoration: none; font-weight: 700; cursor: pointer; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-secondary { background: #e2e8f0; color: #0f172a; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 980px; }
    th, td { padding: 14px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
    th { background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
    tr:hover td { background: #f8fafc; }
    .meta { color: #475569; font-size: 13px; }
    .title { font-weight: 700; color: #0f172a; }
    .badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-amber { background: #fef3c7; color: #b45309; }
    .badge-green { background: #dcfce7; color: #166534; }
    .badge-red { background: #fee2e2; color: #991b1b; }
    .empty { text-align: center; padding: 50px 20px; color: #64748b; }
    .actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .pagination { margin-top: 18px; }
</style>

<div class="page-shell">
    <div class="panel hero">
        <div class="crumbs"><a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a> / Exam Grading</div>
        <h1>Exam Grading</h1>
        <p>Search, filter, and manage exam answer sheets for this course.</p>
    </div>

    <div class="panel">
        <form method="GET" class="filters">
            <div>
                <label for="search">Search</label>
                <input id="search" name="search" value="{{ $search }}" placeholder="Search exam title">
            </div>
            <div>
                <label for="module_id">Chapter / Module</label>
                <select id="module_id" name="module_id">
                    <option value="">All chapters</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}" @selected((string) $selectedModuleId === (string) $module->id)>{{ $module->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort">Sort</label>
                <select id="sort" name="sort">
                    <option value="latest" @selected($sort === 'latest')>Latest</option>
                    <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                </select>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('teacher.courses.exam-grading', $course) }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel table-wrap">
        @if($exams->count())
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Chapter / Unit</th>
                        <th>Exam Date</th>
                        <th>Total Students</th>
                        <th>Submitted</th>
                        <th>Pending</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $row)
                        @php $exam = $row['model']; @endphp
                        <tr>
                            <td>
                                <div class="title">{{ $exam->title }}</div>
                                <div class="meta">{{ ucfirst($exam->type) }}</div>
                            </td>
                            <td class="meta">{{ $row['chapter'] }}</td>
                            <td class="meta">{{ optional($row['due_date'])->format('d M Y') ?? '-' }}</td>
                            <td><span class="badge badge-blue">{{ $row['total_students'] }}</span></td>
                            <td><span class="badge badge-green">{{ $row['submitted'] }}</span></td>
                            <td><span class="badge badge-amber">{{ $row['pending'] }}</span></td>
                            <td>
                                <a href="{{ route('teacher.grading.exams.submissions', $exam) }}" class="btn btn-primary">View Submissions</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $exams->links() }}</div>
        @else
            <div class="empty">
                <h3 style="margin:0 0 8px; color:#0f172a;">No exams found</h3>
                <p style="margin:0;">Try another search or filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
