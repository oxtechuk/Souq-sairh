@foreach($brands as $brand)
  @php $selected = request('brand_id') == $brand->id; @endphp
  <div class="brand-item {{ $selected ? 'selected' : '' }}" data-brand-id="{{ $brand->id }}">
    <div class="brand-info">
      <div class="brand-icon">
        <i class="fas fa-circle"></i>
      </div>
      <span class="brand-name">{{ $brand->name }}</span>
    </div>
    <span class="brand-count">{{ $brand->cars_count }}</span>
  </div>
@endforeach