@extends('layouts.teacher')

@section('title', 'Add Content')
@section('page-title', 'Add Content to Unit')

@section('content')
<style>
    .container {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 32px;
        text-align: center;
    }

    .header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .header p {
        margin: 8px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .body {
        padding: 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-group label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .ai-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #7c3aed;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .ai-section h3 {
        margin: 0 0 16px;
        color: #5b21b6;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-section h3::before {
        content: "🤖";
    }

    .ai-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #7c3aed;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    .btn-ai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #7c3aed;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Add Content to Unit</h1>
        <p>{{ $module->title }} - {{ $course->name }}</p>
    </div>

    <div class="body">
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}">{{ $module->title }}</a>
            <span>/</span>
            <span>Add Content</span>
        </nav>

        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                <strong style="color: #dc2626;">Please fix the following errors:</strong>
                <ul style="margin: 10px 0 0 20px; color: #dc2626;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.courses.modules.items.store', [$course, $module]) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Topic/Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-control"
                       value="{{ old('title') }}"
                       placeholder="e.g., Introduction to Photosynthesis" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="file">Upload unit outline file</label>
                <label class="upload-box" style="display:block; border:2px dashed #e5e7eb; border-radius:8px; padding:28px; text-align:center; cursor:pointer; margin-top:8px;">
                    <div style="font-size:24px; color:#6b7280;">📁</div>
                    <div style="margin-top:8px; font-weight:600;">Upload unit outline file</div>
                    <div style="font-size:12px; color:#9ca3af; margin-top:6px;">PDF, DOC, DOCX, TXT — AI will use this for auto-grading</div>
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this)" style="display:none">
                </label>
                <div id="file-info" style="margin-top: 8px; color: #10b981; font-size: 13px;"></div>
            </div>

            <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(420px, 520px); gap: 16px; margin-bottom: 16px; align-items: start;">
                <div>
                    <div style="background:#fff; border:1px solid #e5e7eb; padding:12px; border-radius:8px; margin-bottom:12px;">
                        <div style="font-weight:700; margin-bottom:8px;">Grade Scale</div>
                        <div style="display:grid; grid-template-columns:1fr 80px 80px; gap:8px; align-items:center">
                            <div>A</div>
                            <input name="grade_scale[a_min]" type="number" value="80" class="form-control">
                            <input name="grade_scale[a_max]" type="number" value="100" class="form-control">
                            <div>B</div>
                            <input name="grade_scale[b_min]" type="number" value="65" class="form-control">
                            <input name="grade_scale[b_max]" type="number" value="79" class="form-control">
                            <div>C</div>
                            <input name="grade_scale[c_min]" type="number" value="50" class="form-control">
                            <input name="grade_scale[c_max]" type="number" value="64" class="form-control">
                            <div>D</div>
                            <input name="grade_scale[d_min]" type="number" value="40" class="form-control">
                            <input name="grade_scale[d_max]" type="number" value="49" class="form-control">
                            <div>E</div>
                            <input name="grade_scale[e_min]" type="number" value="30" class="form-control">
                            <input name="grade_scale[e_max]" type="number" value="39" class="form-control">
                            <div>F</div>
                            <input name="grade_scale[f_min]" type="number" value="0" class="form-control">
                            <input name="grade_scale[f_max]" type="number" value="29" class="form-control">
                        </div>
                    </div>

                    <div style="background:#fff; border:1px solid #e5e7eb; padding:12px; border-radius:8px;">
                        <div style="font-weight:700; margin-bottom:8px;">Grading Criteria</div>
                        <div id="criteria-list">
                            <div class="criterion-row" data-index="0" style="display:grid; grid-template-columns: 1fr 1fr 80px; gap:8px; margin-bottom:8px; align-items:center;">
                                <input type="text" class="criterion-name form-control" placeholder="Conceptual understanding" value="Conceptual understanding" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                                <input type="text" class="criterion-desc form-control" placeholder="Short description" value="Shows understanding of core concepts" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                                <div style="display:flex; gap:8px; align-items:center;"><input type="number" class="criterion-weight form-control" value="30" min="0" style="width:80px; padding:8px; border:1px solid #d1d5db; border-radius:6px;"><button type="button" class="btn btn-outline remove-criterion">×</button></div>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns: 1fr 1fr 80px; gap:8px; margin-top:8px; align-items:center;">
                            <input id="new-criterion-name" placeholder="Criterion name" class="form-control" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                            <input id="new-criterion-desc" placeholder="Short description (optional)" class="form-control" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                            <input id="new-criterion-weight" type="number" min="0" placeholder="Weight" value="10" class="form-control" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                        </div>
                        <div style="display:flex; gap:8px; margin-top:8px; align-items:center;"><button type="button" id="add-criterion-btn" class="btn btn-primary">+ Add Criterion</button></div>
                        <div style="margin-top:8px;">Total weight: <span id="total-weight">30</span>%</div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="grading_criteria" id="grading_criteria_input">
            <input type="hidden" name="grade_scale" id="grade_scale_input">



            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"
                          placeholder="Enter the content description or let AI generate it...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Content</button>
            </div>
        </form>
    </div>
</div>

<script>
let uploadedFile = null;

function handleFileUpload(input) {
    const fileInfo = document.getElementById('file-info');
    if (input.files && input.files[0]) {
        uploadedFile = input.files[0];
        fileInfo.textContent = `✓ File selected: ${uploadedFile.name} (${(uploadedFile.size / 1024).toFixed(2)} KB)`;
    } else {
        uploadedFile = null;
        fileInfo.textContent = '';
    }
}
// Unit outline helpers for Add Content page
(function(){
    function updateTotal(){
        const weights = Array.from(document.querySelectorAll('.criterion-weight')).map(i=>parseFloat(i.value)||0);
        const total = weights.reduce((s,v)=>s+v,0);
        const el = document.getElementById('total-weight'); if(el) el.textContent = total;
    }

    function serializeCriteria(){
        const rows = Array.from(document.querySelectorAll('#criteria-list .criterion-row'));
        const items = rows.map(r=>({
            name: r.querySelector('.criterion-name').value||'',
            description: (r.querySelector('.criterion-desc') ? r.querySelector('.criterion-desc').value : ''),
            weight: parseFloat(r.querySelector('.criterion-weight').value)||0
        }));
        const input = document.getElementById('grading_criteria_input'); if(input) input.value = JSON.stringify(items);
    }

    function serializeGradeScale(){
        const fields = ['a','b','c','d','e','f'];
        const scale = {};
        fields.forEach(f=>{
            const min = document.querySelector(`[name="grade_scale[${f}_min]"]`);
            const max = document.querySelector(`[name="grade_scale[${f}_max]"]`);
            scale[f+'_min'] = min ? min.value : '';
            scale[f+'_max'] = max ? max.value : '';
        });
        const input = document.getElementById('grade_scale_input'); if(input) input.value = JSON.stringify(scale);
    }

    document.addEventListener('click', function(e){
        if(e.target && e.target.id === 'add-criterion-btn'){
            const name = document.getElementById('new-criterion-name').value.trim(); if(!name) return;
            const desc = document.getElementById('new-criterion-desc') ? document.getElementById('new-criterion-desc').value.trim() : '';
            const weightInput = document.getElementById('new-criterion-weight');
            const weight = weightInput ? (parseFloat(weightInput.value) || 0) : 0;
            const list = document.getElementById('criteria-list');
            const idx = list.children.length;
            const row = document.createElement('div'); row.className='criterion-row'; row.dataset.index=idx;
            row.style.display='grid'; row.style.gridTemplateColumns='1fr 1fr 80px'; row.style.gap='8px'; row.style.marginBottom='8px'; row.style.alignItems='center';
            row.innerHTML = `<input type="text" class="criterion-name form-control" placeholder="${name}" value="${name}" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;"><input type="text" class="criterion-desc form-control" placeholder="Short description" value="${desc}" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;"><div style=\"display:flex; gap:8px; align-items:center;\"><input type=\"number\" class=\"criterion-weight form-control\" value=\"10\" min=\"0\" style=\"width:80px; padding:8px; border:1px solid #d1d5db; border-radius:6px;\"><button type=\"button\" class=\"btn btn-outline remove-criterion\">×</button></div>`;
            list.appendChild(row);
            const addedWeightInput = row.querySelector('.criterion-weight');
            if (addedWeightInput) addedWeightInput.value = weight;
            document.getElementById('new-criterion-name').value=''; if(document.getElementById('new-criterion-desc')) document.getElementById('new-criterion-desc').value=''; if(weightInput) weightInput.value='10'; updateTotal(); serializeCriteria();
        }

        if(e.target && e.target.classList && e.target.classList.contains('remove-criterion')){
            const row = e.target.closest('.criterion-row'); if(row){ row.remove(); updateTotal(); serializeCriteria(); }
        }

        // content type is fixed to unit_outline on this page
    });

    document.addEventListener('input', function(e){
        if(e.target && e.target.classList && e.target.classList.contains('criterion-weight')){ updateTotal(); serializeCriteria(); }
        if(e.target && e.target.name && e.target.name.startsWith('grade_scale')){ serializeGradeScale(); }
    });

    // before submit, ensure serialized
    const form = document.querySelector('form[method="POST"][enctype]');
    if(form){
        form.addEventListener('submit', function(){ updateTotal(); serializeCriteria(); serializeGradeScale(); });
    }

    document.addEventListener('DOMContentLoaded', function(){ updateTotal(); serializeCriteria(); serializeGradeScale(); });
})();
</script>
@endsection
