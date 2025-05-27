<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">iŞLEM GEÇMİŞİ</h4>
    </div>
    <div class="pb-20">
        <table
            class="table hover multiple-select-row data-table-export nowrap">

            <thead>
                <tr>
                    <th>Fiş No</th>
                    <th class="table-plus datatable-nosort">Müşteri</th>
                    <th class="table-plus datatable-nosort">Takoz Ağırlığı</th>
                    <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                    <th class="table-plus datatable-nosort">İşlem Gören Miktar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Session'dan role değerini alalım
                $role = session()->get('role');
                ?>

                <?php foreach ($gecmis as $item): ?>
                    <tr>
                        <td><?= esc($item['id']); ?></td>
                        <td class="table-plus"><?= esc($item['musteri']); ?></td>
                        <td><?= number_format(esc($item['giris_gram']), 3); ?> gr</td>
                        <td><?= esc($item['olculen_milyem']); ?></td>
                         <td><?= esc($item['islem_goren_miktar']); ?> gr</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<?php if (isset($eritme_fire)): ?>
    <p><strong>Hurda Eritme Fire:</strong> <?= esc($eritme_fire) ?> gr</p>
<?php endif; ?>
<?php if (isset($reaktor_fire)): ?>
    <p><strong>Hurda Reaktör Fire:</strong> <?= number_format(esc($reaktor_fire),3) ?> gr</p>
<?php endif; ?>