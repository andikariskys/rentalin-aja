<?php
include '../templates/template_admin.php';

header_navbar('Dashboard', 'dashboard');
?>

<center>
    <h2 class="my-3">Data Peminjaman</h2>
</center>
<div class="table table-responsive">
    <table
        class="table table-striped table-hover table-borderless table-light align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Column 2</th>
                <th>Column 3</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <tr>
                <td>Item</td>
                <td>Item</td>
                <td>Item</td>
            </tr>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>


<?php
footer();
?>