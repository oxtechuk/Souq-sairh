// Car Data and Card Generator

// Sample car data
const carsData = [
  {
    id: 1,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  },
  {
    id: 2,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  },
  {
    id: 3,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  },
  {
    id: 4,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  },
  {
    id: 5,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  },
  {
    id: 6,
    year: 2024,
    name: 'كيا سبورتاج',
    model: 'GLS 1.6L',
    image: 'assets/images/car-1.png',
    cashPrice: '92,000',
    originalPrice: '92,000',
    monthlyPayment: '92,000',
    fuel: 'بنزين',
    transmission: 'أوتوماتيك',
    seats: '5.0',
    type: 'SUV'
  }
];

// Generate car card HTML
function generateCarCard(car, cardClass = 'car-card') {
  return `
    <div class="${cardClass} shrink-0 w-[280px] bg-white rounded-[18px] overflow-hidden border border-[#B8C3D8]" dir="rtl">
      <div class="relative bg-[#EEF2F7] h-[215px] px-5 pt-5">
        <span class="absolute top-4 left-5 bg-primary text-white px-5 py-2 rounded-full text-xs font-bold">
          ${car.year}
        </span>

        <img src="${car.image}" alt="${car.name}" class="w-full h-[135px] object-contain mt-8" />

        <button onclick="navigateToCompare()" class="absolute bottom-4 right-5 bg-[#FFF1C2] text-primary px-4 py-2 rounded-full font-bold text-xs flex items-center justify-center gap-2 whitespace-nowrap">
          <span>أضف للمقارنة</span>
          <i class="fas fa-code-compare text-xs"></i>
        </button>
      </div>

      <div class="h-[62px] px-4 flex items-center justify-center border-b border-[#C9D1E2]">
        <h3 class="text-[18px] font-extrabold text-primary text-center leading-tight">
          ${car.name} <span dir="ltr">${car.model}</span>
        </h3>
      </div>

      <div class="grid grid-cols-2 h-[108px] border-b border-[#C9D1E2]">
        <div class="flex flex-col items-center justify-center border-l border-[#C9D1E2]">
          <p class="text-[11px] text-primary mb-1">سعر الكاش</p>
          <p class="text-[17px] font-extrabold text-gold mb-1">${car.cashPrice} ريال</p>
          <p class="text-[11px] text-gray-500 line-through">${car.originalPrice} ريال</p>
        </div>

        <div class="flex flex-col items-center justify-center">
          <p class="text-[11px] text-primary mb-1">القسط الشهري</p>
          <p class="text-[17px] font-extrabold text-gold mb-1">${car.monthlyPayment} ريال</p>
          <p class="text-[11px] text-gray-500">تقديري</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-x-14 gap-y-5 px-6 py-5 text-primary text-[13px]" dir="rtl">
        <div class="flex items-center justify-start gap-2">
          <i class="fas fa-gas-pump w-4 text-center flex-shrink-0"></i>
          <span>${car.fuel}</span>
        </div>

        <div class="flex items-center justify-start gap-2">
          <i class="fas fa-cogs w-4 text-center flex-shrink-0"></i>
          <span>${car.transmission}</span>
        </div>

        <div class="flex items-center justify-start gap-2">
          <i class="fas fa-chair w-4 text-center flex-shrink-0"></i>
          <span dir="ltr">${car.seats}</span>
        </div>

        <div class="flex items-center justify-start gap-2">
          <i class="fas fa-car-side w-4 text-center flex-shrink-0"></i>
          <span dir="ltr">${car.type}</span>
        </div>
      </div>

      <div class="px-8 pb-5">
        <button
          onclick="navigateToCarDetails(${car.id})"
          class="w-full h-[62px] bg-white text-primary border border-primary hover:bg-primary hover:text-white rounded-md font-extrabold text-[20px] transition-all"
        >
          عرض التفاصيل
        </button>
      </div>
    </div>
  `;
}

// Navigate to car details with correct path
function navigateToCarDetails(carId) {
  const isInPages = window.location.pathname.includes('/pages/');
  const detailsPath = isInPages ? 'car-details.html?id=' + carId : 'pages/car-details.html?id=' + carId;
  window.location.href = detailsPath;
}

// Navigate to compare page
function navigateToCompare() {
  const isInPages = window.location.pathname.includes('/pages/');
  const comparePath = isInPages ? 'compare.html' : 'pages/compare.html';
  window.location.href = comparePath;
}

// Generate multiple car cards
function generateCarCards(cars, cardClass = 'car-card') {
  return cars.map(car => generateCarCard(car, cardClass)).join('');
}

// Export for use in other scripts
window.carsData = carsData;
window.generateCarCard = generateCarCard;
window.generateCarCards = generateCarCards;
window.navigateToCarDetails = navigateToCarDetails;
window.navigateToCompare = navigateToCompare;
