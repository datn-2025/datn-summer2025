@extends('layouts.app')
@section('title', 'Liên hệ')
@section('content')

<div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
  <div class="mx-auto max-w-2xl text-center">
    <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Liên hệ với chúng tôi</h2>
    <p class="mt-2 text-lg leading-8 text-gray-600">Hãy gửi góp ý hoặc câu hỏi, chúng tôi sẽ phản hồi qua email của bạn.</p>
    @if(session('success'))
      <div class="mt-4 rounded bg-green-100 p-4 text-green-700">{{ session('success') }}</div>
    @endif
  </div>

  <form method="POST" action="{{ route('contact.submit') }}" class="mx-auto mt-16 max-w-xl sm:mt-20">
    @csrf
    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
      
      <div>
        <label for="name" class="block text-sm font-semibold text-gray-900">Họ tên</label>
        <div class="mt-2.5">
          <input type="text" name="name" id="name" autocomplete="name" required
                 class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400
                        focus:outline-2 focus:outline-indigo-600 focus:outline-offset-2"
                 placeholder="Nhập họ tên" value="{{ old('name') }}">
        </div>
      </div>

      <div>
        <label for="phone" class="block text-sm font-semibold text-gray-900">Số điện thoại</label>
        <div class="mt-2.5">
          <input type="text" name="phone" id="phone" autocomplete="tel" required
                 class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400
                        focus:outline-2 focus:outline-indigo-600 focus:outline-offset-2"
                 placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
        </div>
      </div>

      <div class="sm:col-span-2">
        <label for="email" class="block text-sm font-semibold text-gray-900">Email</label>
        <div class="mt-2.5">
          <input type="email" name="email" id="email" autocomplete="email" required
                 class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400
                        focus:outline-2 focus:outline-indigo-600 focus:outline-offset-2"
                 placeholder="Nhập email" value="{{ old('email') }}">
        </div>
      </div>
      <div class="sm:col-span-2">
        <label for="address" class="block text-sm font-semibold text-gray-900">địa chỉ </label>
        <div class="mt-2.5">
          <input type="text" name="address" id="address" autocomplete="address" required
                 class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400
                        focus:outline-2 focus:outline-indigo-600 focus:outline-offset-2"
                 placeholder="Nhập email" value="{{ old('address') }}">
        </div>
      </div>

      <div class="sm:col-span-2">
        <label for="note" class="block text-sm font-semibold text-gray-900">Nội dung</label>
        <div class="mt-2.5">
          <textarea name="note" id="note" rows="4" required
                    class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400
                           focus:outline-2 focus:outline-indigo-600 focus:outline-offset-2"
                    placeholder="Nhập nội dung">{{ old('note') }}</textarea>
        </div>
      </div>
    </div>
    <div class="mt-10">
      <button type="submit"
              class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs
                     hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Gửi liên hệ
      </button>
    </div>
  </form>
</div>

@endsection
