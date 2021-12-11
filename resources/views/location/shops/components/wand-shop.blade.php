<div class="col-md-12 title">
    <div class="card-title text-center fw-bold mb-4 pb-2 f-36 bb-white">
        <div class="d-flex d-align d-center">
            <span class="mr-2">
                {{ $location->name }}
            </span>
            <span data-toggle="tooltip" data-placement="top" title="Asa kaliteleri hakkında bilgi almak için tıklayın.">
                <i class="fas fa-question-circle c-pointer" data-toggle="modal" data-target="#tierModal"></i>
            </span>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="text-white bg-dark text-center fw-bold">Adı</td>
                <td class="text-white bg-dark text-center fw-bold">Ahşap</td>
                <td class="text-white bg-dark text-center fw-bold">Öz</td>
                <td class="text-white bg-dark text-center fw-bold">Kalite</td>
                <td class="text-white bg-dark text-center fw-bold">Saldırı gücü</td>
                <td class="text-white bg-dark text-center fw-bold">Savunma gücü</td>
                <td class="text-white bg-dark text-center fw-bold">Fiyatı</td>
                <td class="text-white bg-dark text-center fw-bold">Durum</td>
            </tr>
            @foreach ($wands as $item)
                <tr class="">
                    <td data-toggle="tooltip" data-placement="top" title="{{ $item->description }}">
                        {{ $item->short_name }}
                    </td>

                    <td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $item->core->tier->description }}">
                        {{ $item->core->tier->name }}
                    </td>

                    <td class="text-center"
                        data-toggle="tooltip" data-placement="top" title="{{ $item->wood->description }}">
                        {{ $item->wood->name }}
                    </td>

                    <td class="text-center"
                        data-toggle="tooltip" data-placement="top" title="{{ $item->core->description }}">
                        {{ $item->core->core_name }}
                    </td>

                    <td class="text-center">
                        {{ $item->attack_power }}
                    </td>

                    <td class="text-center">
                        {{ $item->defence_power }}
                    </td>
                    @php $discounted_price3 = 0; $discount_description = ""; @endphp
                    @if(Auth::user()->character->core_preffered != null)
                        @if(Auth::user()->character->core_preffered->core_name == $item->core->core_name and $item->discount > 0)
                            @php
                                $discounted_price1 = $item->price * 0.2;
                                $discounted_price2 = ($item->price-$discounted_price1) * $item->discount;
                                $discounted_price3 = $discounted_price1 + $discounted_price2;
                                $discount_description = "Tercih edilen " .Auth::user()->character->core_preffered->core_name . " içeren tüm asalarda %20 indirim ve +%" . $item->discount*100 . " indirim." @endphp
                            <td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $discount_description }}">
                                <p class="text-line-through">{{ $item->price }} G</p>
                                <p>{{ round($item->price-$discounted_price3) }} G</p>
                            </td>
                        @elseif(Auth::user()->character->core_preffered->core_name == $item->core->core_name)
                            @php $discounted_price3 = $item->price * 20 / 100 @endphp
                            @php $discount_description = "Tercih edilen " .Auth::user()->character->core_preffered->core_name . " içeren tüm asalarda %20 indirim." @endphp
                            <td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $discount_description }}">
                                <p class="text-line-through">{{ $item->price }} G</p>
                                <p>{{ round($item->price-$discounted_price3) }} G</p>
                            </td>
                        @elseif($item->discount > 0)
                            @php $discounted_price3 = $item->price * $item->discount @endphp
                            @php $discount_description = "Seçili ürün(ler)de %" . $item->discount*100 . " indirim." @endphp
                            <td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $discount_description }}">
                                <p class="text-line-through">{{ $item->price }} G</p>
                                <p>{{ round($item->price-$discounted_price3) }} G</p>
                            </td>
                        @elseif($item->discount == 0)
                            <td class="text-center">
                                <p>{{ $item->price }} G</p>
                            </td>
                        @endif
                    @else
                        @if($item->discount == 0)
                            <td class="text-center">
                                <p>{{ $item->price }} G</p>
                            </td>
                        @elseif($item->discount > 0)
                            @php $discounted_price3 = $item->price * $item->discount @endphp
                            @php $discount_description = "Seçili ürün(ler)de %" . $item->discount*100 . " indirim." @endphp
                            <td class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $discount_description }}">
                                <p class="text-line-through">{{ $item->price }} G</p>
                                <p>{{ round($item->price-$discounted_price3) }} G</p>
                            </td>
                        @endif
                    @endisset

                    @php $item_status = ""; $item_table_id = ""; $current_item_count = ""; $new_count = ""; @endphp
                    @foreach($character_items->where('character_id', Auth::user()->character->id)
                        ->where('item_id', $item->id) as $character_quill)
                        @php
                            $item_status = 1;
                            $current_item_id = $character_quill->id;
                            $current_item_count = $character_quill->count;
                        @endphp
                    @endforeach
                    <td>
                        @if($item->price > Auth::user()->character->galleon)
                            <p class="fw-bold text-center">
                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Galleon yetersiz"></i>
                            </p>
                        @else
                            <div class="d-flex d-center">
                                <button type="submit" class="ct-btn @include('components.other.class-color-background') text-white" data-toggle="modal" data-target="#shopItem{{ $item->id }}">
                                    Satın al
                                </button>
                            </div>
                        @endif
                    </td>
                </tr>
                @include('components.modals.shop-item-modal')
            @endforeach
        </table>
    </div>
    {{ $wands->links() }}
    @include('components.modals.tier-modal')
</div>