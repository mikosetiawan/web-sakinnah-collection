 <aside id="sidebar"
     class="fixed inset-y-0 left-0 w-64 bg-primary text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50">
     <div class="p-4 text-xl sm:text-2xl font-bold flex justify-between items-center">
         <a href="">
             <img src="{{ asset('sakinah_gallery/sakinah_collection_logos2.png') }}" style="width: 80px;" alt="">
             <p>Sakinnah Collection</p>
         </a>
         <button id="close-sidebar" class="md:hidden text-white focus:outline-none">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                 </path>
             </svg>
         </button>
     </div>
     <nav class="flex-1">
         <ul class="space-y-2 p-4">
             <li>
                 <a href="{{ route('dashboard') }}"
                     class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                     <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                         <path
                             d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 10a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                         </path>
                     </svg>
                     Dashboard
                 </a>
             </li>
             <li>
                 @if (auth()->user()->role == 'admin')
                     <a href="{{ route('admin.transactions.index') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                             <path fill-rule="evenodd"
                                 d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                 clip-rule="evenodd"></path>
                         </svg>
                         Pemesanan
                     </a>
                 @elseif (auth()->user()->role == 'user')
                     <a href="{{ route('transactions.history') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                             <path fill-rule="evenodd"
                                 d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                 clip-rule="evenodd"></path>
                         </svg>
                         History Pemesanan
                     </a>
                 @endif
             </li>
             <li>
                 @if (auth()->user()->role == 'admin')
                     <a href="{{ url('/jasas') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path
                                 d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                             </path>
                         </svg>
                         Kelola Jasa
                     </a>
                 @endif
             </li>
             <li>
                 @if (auth()->user()->role == 'admin')
                     <a href="{{ url('/barangs') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path
                                 d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                             </path>
                         </svg>
                         Kelola Produk
                     </a>
                 @endif
             </li>
             <li>
                 @if (auth()->user()->role == 'admin')
                     <a href="{{ route('admin.users.index') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd"
                                 d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8h8V6zM6 7a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 2a1 1 0 100 2h6a1 1 0 100-2H7z"
                                 clip-rule="evenodd"></path>
                         </svg>
                         Kelola User
                     </a>
                 @endif
             </li>
             <li>
                 @if (auth()->user()->role == 'admin' || auth()->user()->role == 'ceo')
                     <a href="{{ route('admin.reports.index') }}"
                         class="flex items-center p-2 hover:bg-primary-hover rounded text-sm sm:text-base">
                         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd"
                                 d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8h8V6zM6 7a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 2a1 1 0 100 2h6a1 1 0 100-2H7z"
                                 clip-rule="evenodd"></path>
                         </svg>
                         Report
                     </a>
                 @endif
             </li>
         </ul>
     </nav>
 </aside>
