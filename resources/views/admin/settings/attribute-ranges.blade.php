@extends('admin.layouts.app')

@section('title', 'Pengaturan Rentang Atribut')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Rentang Atribut</h6>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Atribut</th>
                                    <th>Nilai Minimum</th>
                                    <th>Nilai Maksimum</th>
                                    <th>Jumlah Grup</th>
                                    <th>Rentang Grup</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranges as $range)
                                    <tr>
                                        <td>{{ $range->display_name }}</td>
                                        <td>
                                            @if($range->attribute_name === 'harga_sewa')
                                                Rp {{ number_format($range->min_value, 0, ',', '.') }}
                                            @elseif($range->attribute_name === 'estimasi_jarak')
                                                {{ number_format($range->min_value, 1, ',', '.') }} km
                                            @else
                                                {{ number_format($range->min_value, 1, ',', '.') }} m²
                                            @endif
                                        </td>
                                        <td>
                                            @if($range->attribute_name === 'harga_sewa')
                                                Rp {{ number_format($range->max_value, 0, ',', '.') }}
                                            @elseif($range->attribute_name === 'estimasi_jarak')
                                                {{ number_format($range->max_value, 1, ',', '.') }} km
                                            @else
                                                {{ number_format($range->max_value, 1, ',', '.') }} m²
                                            @endif
                                        </td>
                                        <td>{{ $range->number_of_groups }}</td>
                                        <td>
                                            @if($range->group_ranges)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($range->group_ranges as $group)
                                                        <li class="small">{{ $group['label'] }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">Belum dihitung</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                data-toggle="modal" 
                                                data-target="#editRangeModal"
                                                data-id="{{ $range->id }}"
                                                data-name="{{ $range->display_name }}"
                                                data-min="{{ $range->min_value }}"
                                                data-max="{{ $range->max_value }}"
                                                data-groups="{{ $range->number_of_groups }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Range Modal -->
<div class="modal fade" id="editRangeModal" tabindex="-1" role="dialog" aria-labelledby="editRangeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editRangeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editRangeModalLabel">Edit Rentang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="range_id" id="rangeId">
                    <div class="form-group">
                        <label for="rangeName">Nama Atribut</label>
                        <input type="text" class="form-control" id="rangeName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="minValue">Nilai Minimum</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="minValue" name="min_value" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="minSuffix"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="maxValue">Nilai Maksimum</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="maxValue" name="max_value" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="maxSuffix"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="numberOfGroups">Jumlah Grup</label>
                        <select class="form-control" id="numberOfGroups" name="number_of_groups" required>
                            @for($i = 2; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>{{ $i }} Grup</option>
                            @endfor
                        </select>
                        <small class="form-text text-muted">Rentang nilai akan dibagi menjadi jumlah grup yang ditentukan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle edit button click
        $('#editRangeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var min = button.data('min');
            var max = button.data('max');
            var groups = button.data('groups');
            
            var modal = $(this);
            modal.find('#rangeId').val(id);
            modal.find('#rangeName').val(name);
            modal.find('#minValue').val(min);
            modal.find('#maxValue').val(max);
            modal.find('#numberOfGroups').val(groups);
            
            // Update form action
            var url = '{{ route("admin.settings.attribute-ranges.update", "") }}' + '/' + id;
            modal.find('form').attr('action', url);
            
            // Update suffixes based on attribute name
            var attributeName = button.closest('tr').find('td:first').text().trim().toLowerCase();
            if (attributeName.includes('harga')) {
                $('#minSuffix, #maxSuffix').text('IDR');
            } else if (attributeName.includes('jarak')) {
                $('#minSuffix, #maxSuffix').text('km');
            } else {
                $('#minSuffix, #maxSuffix').text('m²');
            }
        });
        
        // Form validation
        $('#editRangeForm').validate({
            rules: {
                min_value: {
                    required: true,
                    number: true,
                    min: 0
                },
                max_value: {
                    required: true,
                    number: true,
                    min: function() {
                        return parseFloat($('#minValue').val()) + 0.01;
                    }
                },
                number_of_groups: {
                    required: true,
                    min: 2,
                    max: 10,
                    digits: true
                }
            },
            messages: {
                max_value: {
                    min: "Nilai maksimum harus lebih besar dari nilai minimum"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
