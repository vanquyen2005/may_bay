<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

$page_title = 'Trang chủ';
$current_page = 'home';
include __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="/maybay/css/style.css">

<!-- Hero Section -->
<div class="relative overflow-hidden rounded-3xl mx-4 mt-8">
    <!-- Background gradient + overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-500"></div>
    <div class="absolute inset-0 bg-black/25"></div>

    <div class="relative px-6 py-16 md:px-12 md:py-24 text-white">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">Đặt Vé Máy Bay Dễ Dàng</h1>
            <p class="text-xl opacity-90 mb-8 drop-shadow-md">
                Tìm kiếm và đặt vé máy bay với giá tốt nhất. Hàng trăm chuyến bay khởi hành mỗi ngày.
            </p>
        </div>

        <!-- Search Form -->
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-2xl max-w-5xl mx-auto">
            <form action="/search_flights.php" method="GET" id="searchForm" class="space-y-4">
                <!-- Trip Type Toggle -->
                <div class="flex gap-4 justify-center">
                    <button type="button" class="px-6 py-2 rounded-full bg-blue-600 text-white font-semibold transition" id="oneWayBtn">Một chiều</button>
                    <button type="button" class="px-6 py-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold transition" id="roundTripBtn">Khứ hồi</button>
                </div>
                <input type="hidden" name="trip_type" id="tripType" value="one-way">

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Điểm đi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đi</label>
                        <select name="from" id="from" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition text-black placeholder-gray-400">
                            <option value="" disabled selected hidden>Chọn điểm đi</option>
                            <?php foreach ($airports as $airport): ?>
                                <option value="<?php echo $airport['code']; ?>"><?php echo $airport['city'] . ' (' . $airport['code'] . ')'; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Điểm đến -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
                        <select name="to" id="to" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition text-black placeholder-gray-400">
                            <option value="" disabled selected hidden>Chọn điểm đến</option>
                            <?php foreach ($airports as $airport): ?>
                                <option value="<?php echo $airport['code']; ?>"><?php echo $airport['city'] . ' (' . $airport['code'] . ')'; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Ngày bay -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày bay</label>
                        <input type="date" name="date" id="date" required 
                               min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <!-- Số hành khách -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số hành khách</label>
                        <select name="passengers" id="passengers" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition text-black placeholder-gray-400">
                            <?php for ($i=1; $i<=9; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> người</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                    <i class="fas fa-search mr-2"></i> Tìm chuyến bay
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container mx-auto px-4 py-12 space-y-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        $features = [
            ['icon'=>'shield-alt','title'=>'An Toàn & Bảo Mật','desc'=>'Thông tin của bạn được bảo vệ tuyệt đối'],
            ['icon'=>'clock','title'=>'Đặt Vé Nhanh Chóng','desc'=>'Chỉ trong vài phút, vé của bạn sẽ sẵn sàng'],
            ['icon'=>'star','title'=>'Giá Tốt Nhất','desc'=>'Cam kết giá rẻ nhất thị trường']
        ];
        foreach($features as $f): ?>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-2xl transition cursor-pointer">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                <i class="fas fa-<?php echo $f['icon']; ?> text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2"><?php echo $f['title']; ?></h3>
            <p class="text-gray-600"><?php echo $f['desc']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Popular Destinations -->
    <?php if(!empty($popular_destinations)): ?>
    <div>
        <h2 class="text-3xl font-bold mb-6">Điểm Đến Phổ Biến</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach($popular_destinations as $dest): ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition cursor-pointer" onclick="searchDestination('<?php echo $dest['code']; ?>')">
                <div class="relative h-48">
                    <img src="<?php echo $dest['image'] ?? '/maybay/img/default.jpg'; ?>" alt="<?php echo $dest['name']; ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/30 flex flex-col justify-end p-4 text-white">
                        <h3 class="text-2xl font-bold"><?php echo $dest['name']; ?></h3>
                        <p class="text-sm opacity-90"><?php echo $dest['code']; ?></p>
                    </div>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Giá từ</p>
                        <p class="text-xl font-bold text-blue-600"><?php echo $dest['from_price']; ?></p>
                    </div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Đặt ngay <i class="fas fa-arrow-right ml-1"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const oneWayBtn = document.getElementById('oneWayBtn');
const roundTripBtn = document.getElementById('roundTripBtn');
const tripType = document.getElementById('tripType');

oneWayBtn.addEventListener('click',()=>{
    oneWayBtn.classList.replace('bg-gray-100','bg-blue-600');
    oneWayBtn.classList.replace('text-gray-700','text-white');
    roundTripBtn.classList.replace('bg-blue-600','bg-gray-100');
    roundTripBtn.classList.replace('text-white','text-gray-700');
    tripType.value='one-way';
});
roundTripBtn.addEventListener('click',()=>{
    roundTripBtn.classList.replace('bg-gray-100','bg-blue-600');
    roundTripBtn.classList.replace('text-gray-700','text-white');
    oneWayBtn.classList.replace('bg-blue-600','bg-gray-100');
    oneWayBtn.classList.replace('text-white','text-gray-700');
    tripType.value='round-trip';
});

function searchDestination(code){
    document.getElementById('to').value=code;
    document.getElementById('searchForm').submit();
}

document.getElementById('searchForm').addEventListener('submit',function(e){
    if(document.getElementById('from').value===document.getElementById('to').value){
        e.preventDefault();
        alert('Điểm đi và điểm đến phải khác nhau!');
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
