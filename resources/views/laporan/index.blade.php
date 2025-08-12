<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cetak Laporan Transaksi</h2>
  </x-slot>
<style>
  .btn-base { padding: .5rem 1rem; border-radius: .5rem; font-weight:600; }
</style>

  <div class="max-w-6xl mx-auto py-8 px-4">
    <div x-data="reportUI()" class="bg-white rounded-2xl shadow p-6 space-y-6">

      <!-- Toolbar: tombol kesamping -->
        <div class="flex flex-wrap gap-3">
            <button @click="setTab('harian')"   :class="btnClass('harian','active-blue')"   class="btn-base">Harian</button>
            <button @click="setTab('mingguan')" :class="btnClass('mingguan','active-green')" class="btn-base">Mingguan</button>
            <button @click="setTab('bulanan')"  :class="btnClass('bulanan','active-orange')" class="btn-base">Bulanan</button>
            <button @click="setTab('tahunan')"  :class="btnClass('tahunan','active-purple')" class="btn-base">Tahunan</button>
        </div>
      <!-- Bar filter + aksi untuk tab aktif -->
      <div class="flex flex-wrap items-end justify-between gap-4 border-b pb-4">
        <div class="flex items-end gap-3">
          <!-- Harian -->
          <template x-if="active==='harian'">
            <div>
              <label class="block text-sm font-semibold mb-1">Tanggal</label>
              <input type="date" x-model="form.tanggal" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
            </div>
          </template>

          <!-- Mingguan -->
          <template x-if="active==='mingguan'">
            <div>
              <label class="block text-sm font-semibold mb-1">Minggu (ISO)</label>
              <input type="week" x-model="form.minggu" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-300">
            </div>
          </template>

          <!-- Bulanan -->
          <template x-if="active==='bulanan'">
            <div>
              <label class="block text-sm font-semibold mb-1">Bulan</label>
              <input type="month" x-model="form.bulan" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-300">
            </div>
          </template>

          <!-- Tahunan -->
          <template x-if="active==='tahunan'">
           <div class="relative inline-block">
            <select x-model="form.tahun"
                    class="border rounded-lg px-3 py-2 pr-10 bg-white
                            ring-1 ring-gray-300
                            appearance-none
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @for($y = now('Asia/Jakarta')->year; $y >= now('Asia/Jakarta')->year - 5; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            <!-- ikon panah custom -->
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500"
                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
            </div>
          </template>

          <button @click="loadPreview()" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-black">Review</button>
        </div>

        <!-- Cetak PDF (action berubah sesuai tab) -->
        <form :action="printAction()" method="GET" target="_blank" @submit="syncForm($event)">
          <input type="hidden" name="tanggal" :value="form.tanggal">
          <input type="hidden" name="minggu"  :value="form.minggu">
          <input type="hidden" name="bulan"   :value="form.bulan">
          <input type="hidden" name="tahun"   :value="form.tahun">
          <button class="px-5 py-2 rounded-lg bg-gray-800 text-white hover:bg-black">Cetak PDF</button>
        </form>
      </div>

      <!-- PREVIEW TABEL: lebih lega -->
      <div class="rounded-xl border bg-gray-50">
        <div class="px-4 py-3 text-sm text-gray-600 flex items-center gap-2">
          <span x-text="subtitle()"></span>
          <span x-show="loading" class="animate-pulse">· memuat…</span>
        </div>
        <div id="preview" class="bg-white rounded-b-xl overflow-auto max-h-[70vh]"></div>
      </div>

    </div>
  </div>

  <script>
function reportUI(){
  return {
    active: 'harian',
    // ...
    btnClass(tab, activeKey){
      const activeMap = {
        'active-blue':   'bg-blue-600 text-white hover:bg-blue-700',
        'active-green':  'bg-green-600 text-white hover:bg-green-700',
        'active-orange': 'bg-orange-500 text-white hover:bg-orange-600',
        'active-purple': 'bg-purple-600 text-white hover:bg-purple-700',
      };
      const idle = 'bg-gray-200 text-gray-700 hover:bg-gray-300';
      return (this.active === tab) ? activeMap[activeKey] : idle;
    },
    setTab(t){ this.active = t; },
    // ... (fungsi lain tetap)
  }
}
</script>
</x-app-layout>
