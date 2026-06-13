// Testimonials data and card generator

const testimonialsData = [
  { id: 1, text: 'افضل معرض للسيارات الفاخرة في الرياض، خيارات متنوعة والاسعار ممتازة والتعامل سهل.', rating: 5, customerName: 'محمد القحطاني', customerTitle: 'مهندس', customerInitial: 'م' },
  { id: 2, text: 'معرض السيارات الكهربائية في جدة يضم احدث الطرازات من الشركات العالمية وخدمات متكاملة.', rating: 5, customerName: 'سارة الرفاعي', customerTitle: 'مستشارة تقنية', customerInitial: 'س' },
  { id: 3, text: 'معرض السيارات الكلاسيكية في الدمام يتيح للزوار تجربة سيارات تاريخية نادرة وصيانتها.', rating: 5, customerName: 'احمد السالم', customerTitle: 'طبيب', customerInitial: 'ا' },
  { id: 4, text: 'خدمة ممتازة وفريق متعاون جدا، ساعدوني في اختيار السيارة المناسبة لميزانيتي بكل سهولة.', rating: 5, customerName: 'خالد العتيبي', customerTitle: 'رجل اعمال', customerInitial: 'خ' },
  { id: 5, text: 'تجربة شراء رائعة من البداية للنهاية، الاسعار تنافسية وخيارات التمويل مرنة جدا.', rating: 5, customerName: 'نورة الشمري', customerTitle: 'معلمة', customerInitial: 'ن' },
  { id: 6, text: 'انصح الجميع بزيارة سوق سيارة، الموظفون محترفون والسيارات بحالة ممتازة.', rating: 5, customerName: 'فيصل الدوسري', customerTitle: 'محاسب', customerInitial: 'ف' },
  { id: 7, text: 'حصلت على سيارتي بافضل سعر واسرع وقت، الخدمة احترافية والتوصيل كان في الموعد.', rating: 5, customerName: 'ريم الزهراني', customerTitle: 'مديرة مشاريع', customerInitial: 'ر' },
  { id: 8, text: 'تعاملت معهم مرتين وفي كل مرة تجربة افضل، ثقة عالية وشفافية تامة في التسعير.', rating: 5, customerName: 'عبدالله المطيري', customerTitle: 'مقاول', customerInitial: 'ع' },
  { id: 9, text: 'من افضل التجارب التي مررت بها في شراء سيارة، سهولة في الاجراءات وسرعة في التسليم.', rating: 5, customerName: 'هند الغامدي', customerTitle: 'صيدلانية', customerInitial: 'ه' }
];

function generateTestimonialCardHTML(t) {
  var stars = '';
  for (var s = 0; s < t.rating; s++) stars += '<i class="fas fa-star"></i>';
  return '<div class="testimonial-card">' +
    '<div class="quote-icon"><svg width="48" height="48" viewBox="0 0 48 48" fill="none"><path d="M12 28C12 24.6863 14.6863 22 18 22V18C12.4772 18 8 22.4772 8 28V36H20V28H12Z" fill="#d4a017"/><path d="M32 28C32 24.6863 34.6863 22 38 22V18C32.4772 18 28 22.4772 28 28V36H40V28H32Z" fill="#d4a017"/></svg></div>' +
    '<p class="testimonial-text">' + t.text + '</p>' +
    '<div class="rating-stars">' + stars + '</div>' +
    '<div class="customer-info"><div class="customer-avatar"><span>' + t.customerInitial + '</span></div><div class="customer-details"><h4 class="customer-name">' + t.customerName + '</h4><p class="customer-title">' + t.customerTitle + '</p></div></div>' +
    '</div>';
}

function generateTestimonialCard(t) {
  var div = document.createElement('div');
  div.innerHTML = generateTestimonialCardHTML(t).trim();
  return div.firstChild;
}

window.testimonialsData = testimonialsData;
window.generateTestimonialCard = generateTestimonialCard;
window.generateTestimonialCardHTML = generateTestimonialCardHTML;
