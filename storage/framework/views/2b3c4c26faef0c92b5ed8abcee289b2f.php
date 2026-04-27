

<?php $__env->startSection('content'); ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        <div class="row row-deck row-cards">

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-warning text-white">
                                    <i class="fa-solid fa-clock"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?php echo e($pendingDocuments); ?> Pending
                                </div>
                                <div class="text-muted">Submitted</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-primary text-white">
                                    <i class="fa-solid fa-spinner"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?php echo e($processingDocuments); ?> Processing
                                </div>
                                <div class="text-muted">In progress</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-info text-white">
                                    <i class="fa-solid fa-box-archive"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?php echo e($readyDocuments); ?> Ready
                                </div>
                                <div class="text-muted">For Claiming</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar bg-success text-white">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?php echo e($claimedDocuments); ?> Claimed
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Documents Overview</h3>

                        <select id="documentsRange" class="form-select w-auto">
                            <option value="daily" <?php echo e($range == 'daily' ? 'selected' : ''); ?>>Daily</option>
                            <option value="weekly" <?php echo e($range == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                            <option value="monthly" <?php echo e($range == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                            <option value="yearly" <?php echo e($range == 'yearly' ? 'selected' : ''); ?>>Last 10 Years</option>
                        </select>
                    </div>

                    <div class="card-body position-relative">
                        <div id="documents-loading"
                             class="position-absolute top-0 bottom-0 start-0 end-0 justify-content-center align-items-center bg-white bg-opacity-75"
                             style="display:none;z-index:10;">
                            <div class="spinner-border text-primary"></div>
                        </div>

                        <div id="documents-chart" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('page-scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
let documentsChart

function renderDocumentsChart(data) {
    if (documentsChart) documentsChart.destroy()

    const options = {
        chart: { type: "area", height: 300, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        series: [{ name: "Documents", data: data.counts }],
        xaxis: { categories: data.dates },
        colors: ["#720100"],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            y: { formatter: val => val + ' documents' }
        }
    }

    documentsChart = new ApexCharts(
        document.querySelector("#documents-chart"),
        options
    )
    documentsChart.render()
}

$(document).ready(function () {
    renderDocumentsChart(<?php echo json_encode($documentsGraphData, 15, 512) ?>)

    $('#documentsRange').on('change', function () {
        $('#documents-loading').css('display', 'flex')

        $.get("<?php echo e(route('dashboard.data')); ?>", {
            range: $(this).val(),
            ajax: true
        }, function (response) {
            $('#documents-loading').hide()
            renderDocumentsChart(response.documentsGraphData)
        }).fail(() => {
            $('#documents-loading').hide()
        })
    })
})
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/admin/index.blade.php ENDPATH**/ ?>