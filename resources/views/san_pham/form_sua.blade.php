@extends('layouts.app')

@section('tieude', 'Chỉnh sửa sản phẩm')

@section('noi-dung')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">✏️ Chỉnh sửa sản phẩm: {{ $sanPham->ten_san_pham }}</h5>
            </div>
            
            <div class="card-body">
                <form action="/san-pham/{{ $sanPham->id }}/cap-nhat" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Tên sản phẩm -->
                        <div class="col-md-6 mb-3">
                            <label for="ten_san_pham" class="form-label">
                                Tên sản phẩm <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('ten_san_pham') is-invalid @enderror" 
                                   id="ten_san_pham" 
                                   name="ten_san_pham" 
                                   value="{{ old('ten_san_pham', $sanPham->ten_san_pham) }}"
                                   placeholder="Nhập tên sản phẩm"
                                   required>
                            @error('ten_san_pham')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Danh mục -->
                        <div class="col-md-6 mb-3">
                            <label for="danh_muc_id" class="form-label">
                                Danh mục <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('danh_muc_id') is-invalid @enderror" 
                                    id="danh_muc_id" 
                                    name="danh_muc_id" 
                                    required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($danhSachDanhMuc as $danhMuc)
                                    <option value="{{ $danhMuc->id }}" 
                                            {{ (old('danh_muc_id', $sanPham->danh_muc_id) == $danhMuc->id) ? 'selected' : '' }}>
                                        {{ $danhMuc->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('danh_muc_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" 
                                  id="mo_ta" 
                                  name="mo_ta" 
                                  rows="3"
                                  placeholder="Nhập mô tả chi tiết về sản phẩm">{{ old('mo_ta', $sanPham->mo_ta) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <!-- Giá -->
                        <div class="col-md-4 mb-3">
                            <label for="gia" class="form-label">
                                Giá (VNĐ) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('gia') is-invalid @enderror" 
                                   id="gia" 
                                   name="gia" 
                                   value="{{ old('gia', $sanPham->gia) }}"
                                   min="0"
                                   step="1000"
                                   placeholder="0"
                                   required>
                            @error('gia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Số lượng -->
                        <div class="col-md-4 mb-3">
                            <label for="so_luong" class="form-label">
                                Số lượng <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('so_luong') is-invalid @enderror" 
                                   id="so_luong" 
                                   name="so_luong" 
                                   value="{{ old('so_luong', $sanPham->so_luong) }}"
                                   min="0"
                                   placeholder="0"
                                   required>
                            @error('so_luong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Trạng thái -->
                        <div class="col-md-4 mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select class="form-select" id="trang_thai" name="trang_thai">
                                <option value="co_hang" {{ $sanPham->trang_thai == 'co_hang' ? 'selected' : '' }}>Còn hàng</option>
                                <option value="het_hang" {{ $sanPham->trang_thai == 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Các nút hành động -->
                    <div class="d-flex justify-content-between">
                        <a href="/san-pham/{{ $sanPham->id }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            💾 Cập nhật sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
