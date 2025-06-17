@extends('admin.layouts.app')

@section('title', 'Pengaturan Bobot')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Bobot Kriteria</h6>
                    <div class="total-weight-badge">
                        <span class="badge" :class="getTotalWeightClass()">
                            Total: @{{ parseFloat(totalWeight).toFixed(4) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.weights.update') }}" method="POST" id="weightForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered" id="weightsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 40%;">Kriteria</th>
                                        <th style="width: 20%;">Tipe</th>
                                        <th style="width: 20%;">Bobot</th>
                                        <th style="width: 20%;">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weights as $weight)
                                        <tr>
                                            <td>
                                                {{ $weight->display_name }}
                                            </td>
                                            <td>
                                                <select name="criteria_types[{{ $weight->id }}]" 
                                                    class="form-control form-control-sm"
                                                    v-model="criteriaTypes[{{ $weight->id }}]"
                                                    @change="updateWeightTotal">
                                                    <option value="benefit" {{ $weight->criteria_type === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                    <option value="cost" {{ $weight->criteria_type === 'cost' ? 'selected' : '' }}>Cost</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                        name="weights[{{ $weight->id }}]" 
                                                        class="form-control weight-input"
                                                        step="0.01"
                                                        min="0"
                                                        max="1"
                                                        v-model="weights[{{ $weight->id }}]"
                                                        @input="updateWeightTotal">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                        :style="{ width: (weights[{{ $weight->id }}] * 100) + '%' }" 
                                                        :aria-valuenow="weights[{{ $weight->id }}] * 100" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                        @{{ (weights[{{ $weight->id }}] * 100).toFixed(1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" :disabled="!isValidTotal">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress {
        height: 25px;
    }
    .progress-bar {
        line-height: 25px;
        font-size: 0.8rem;
    }
    .weight-input {
        text-align: right;
    }
    .total-weight-badge .badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-danger {
        background-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    new Vue({
        el: '#weightForm',
        data: {
            weights: {!! $weights->pluck('weight', 'id')->toJson() !!},
            criteriaTypes: {!! $weights->pluck('criteria_type', 'id')->toJson() !!},
            totalWeight: {{ $totalWeight }}
        },
        computed: {
            isValidTotal() {
                return Math.abs(this.totalWeight - 1) < 0.0001;
            }
        },
        methods: {
            updateWeightTotal() {
                // Calculate total weight
                this.totalWeight = Object.values(this.weights).reduce((sum, weight) => {
                    return sum + parseFloat(weight || 0);
                }, 0);
            },
            getTotalWeightClass() {
                return Math.abs(this.totalWeight - 1) < 0.0001 ? 'badge-success' : 'badge-danger';
            }
        },
        mounted() {
            // Initialize total weight
            this.updateWeightTotal();
        }
    });
</script>
@endpush
