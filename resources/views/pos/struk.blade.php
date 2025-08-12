<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Struk</title>
<style>
  @page { size: 58mm auto; margin: 0; }

  body { font-family: monospace; font-size: 11px; margin: 0; padding: 0; }
  pre  { margin: 0; padding: 0 4px; line-height: 1.2; }

  /* ATURAN SPACER (bisa kamu ubah angkanya) */
  .safe-top   { height: 12mm; position: relative; }
  .tear-feed  { height: 26mm; position: relative; }

  /* Garis tipis hitam sebagai "anchor" supaya printer maju kertas */
  .safe-top::after{
    content:""; position:absolute; left:0; right:0; bottom:0;
    height: 0.25mm; background:#000;
  }
  .tear-feed::before{
    content:""; position:absolute; left:0; right:0; bottom:0;
    height: 0.25mm; background:#000;
  }

  /* Opsional: kalau masih ngeyel, naikkan height safe-top/tear-feed */
</style>
</head>
<body>

<!-- ANCHOR ATAS (dorong header turun, bikin printer “mengakui” ruang atas) -->
<div class="safe-top"></div>

<pre>
      Gianthill
       INVOICE
{{ now()->format('Y-m-d H:i:s') }}
------------------------------
Item         Qty   Harga   Sub
------------------------------
@foreach ($items as $item)
{{ str_pad(substr($item['item'],0,10),12) }}{{ str_pad($item['quantity'],4,' ',STR_PAD_LEFT) }}{{ str_pad(number_format($item['price']),8,' ',STR_PAD_LEFT) }}{{ str_pad(number_format($item['subtotal']),8,' ',STR_PAD_LEFT) }}
@endforeach
------------------------------
Total                 {{ str_pad(number_format($transaction->total),8,' ',STR_PAD_LEFT) }}
Bayar                 {{ str_pad(number_format($transaction->paid),8,' ',STR_PAD_LEFT) }}
Kembali               {{ str_pad(number_format($transaction->change),8,' ',STR_PAD_LEFT) }}
------------------------------
Terima kasih atas pembelian Anda!
<div style="color: white;">
-------------------------------
--------------------------------
-------------Halo---------------
</div>










</pre>

<!-- ANCHOR BAWAH (paksa kertas maju cukup untuk sobekan) -->
<div class="tear-feed"></div>

<script>
window.onload = function(){
  window.print();
  window.onafterprint = function(){
    window.location.href = "{{ route('pos.index') }}";
  };
};
</script>
</body>
</html>
