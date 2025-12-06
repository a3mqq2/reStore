@extends('layouts.app')

@section('title', 'عرض المستخدمين')

@section('content')
<div class="">
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('users.create') }}" class="btn btn-success">إنشاء مستخدم جديد</a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">قائمة المستخدمين</div>
            <div class="card-body">
                <!-- Filter form -->
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="name" class="form-control" placeholder="ابحث بالاسم" value="{{ request('name') }}">
                        </div>
                        <div class="col">
                            <input type="text" name="email" class="form-control" placeholder="ابحث بالبريد الإلكتروني" value="{{ request('email') }}">
                        </div>
                        <!-- You can add more filters here -->
                        <div class="col">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </div>
                </form>

                <!-- Users list -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-light" scope="col">#</th>
                                <th class="bg-light" scope="col">الاسم</th>
                                <th class="bg-light" scope="col">البريد الإلكتروني</th>
                                <th class="bg-light" scope="col"> رقم الهاتف</th>
                                <th class="bg-light" scope="col">الصلاحيات</th>
                                <th class="bg-light" scope="col">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @foreach($user->permissions as $permission)
                                            <span class="badge badge-primary alert-primary">{{ $permission->ar_name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm"> <i class="fa fa-edit"></i> تعديل</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')"> <i class="fa fa-trash"></i> حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">لا يوجد مستخدمين متاحين</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
