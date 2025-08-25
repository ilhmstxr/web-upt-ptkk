
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-10">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h3 class="font-medium text-indigo-700 flex items-center">
            <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
            Data Peserta
        </h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-500">Nama</p>
            <p class="font-medium">{{ $peserta->nama }}</p>
        </div>
        <div>
            <p class="text-gray-500">Email</p>
            <p class="font-medium">{{ $peserta->email }}</p>
        </div>
    </div>
</div>