@extends('layouts.app')

@section('tieude', 'Kết quả tìm kiếm: ' . $tuKhoa)

@section('noi-dung')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🔍 Kết quả tìm kiếm: "{{ $tuKhoa }}"</h5>
                <a href="/san-pham" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
            
            <div class="card-body">
                @if($ketQuaTimKiem->count() > 0)
                    <!-- Hiển thị số lượng kết quả -->
                    <div class="alert alert-info">
                        Tìm thấy <strong>{{ $ketQuaTimKiem->count() }}</strong> sản phẩm phù hợp
                    </div>
                    
                    <!-- Bảng kết quả tìm kiếm -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Mô tả</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ketQuaTimKiem as $sanPham)
                                    <tr>
                                        <td>{{ $sanPham->id }}</td>
                                        <td>
                                            <a href="/san-pham/{{ $sanPham->id }}">
                                                {{ $sanPham->ten_san_pham }}
                                            </a>
                                        </td>
                                        <td>{{ mb_substr($sanPham->mo_ta ?? 'Không có mô tả', 0, 50) }}</td>
                                        <td>{{ number_format($sanPham->gia, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $sanPham->so_luong }}</td>
                                        <td>
                                            @if($sanPham->trang_thai == 'co_hang')
                                                <span class="badge bg-success">Còn hàng</span>
                                            @else
                                                <span class="badge bg-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('san-pham.chi-tiet', $sanPham->id) }}" 
                                                   class="btn btn-info" title="Xem chi tiết">
                                                    👁️
                                                </a>
                                                <a href="{{ route('san-pham.form-sua', $sanPham->id) }}" 
                                                   class="btn btn-warning" title="Sửa">
                                                    ✏️
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Thông báo khi không tìm thấy kết quả -->
                    <div class="text-center py-5">
                        <h4 class="text-muted">🔍 Không tìm thấy sản phẩm nào</h4>
                        <p class="text-muted">
                            Thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm
                        </p>
                        <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
