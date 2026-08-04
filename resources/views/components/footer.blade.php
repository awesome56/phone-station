@props([])

<footer class="bg-ink text-white">
    {{-- NEWSLETTER --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 pt-16 lg:pt-24 pb-12">
        <div class="grid grid-cols-12 gap-8 items-center">
            <div class="col-span-12 lg:col-span-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Phone Station" class="h-10 w-auto object-contain">
                    <span class="font-semibold text-xl tracking-tight">Phone Station</span>
                </div>
                <h2 class="mt-8 font-medium" style="font-size: clamp(1.9rem, 3.2vw, 2.4rem); line-height: 1.2;">
                    Sign Up To Our Newsletter.
                </h2>
                <p class="mt-3 font-light text-base text-white/90">Be the first to hear about the latest offers.</p>
            </div>

            <div class="col-span-12 lg:col-span-5 lg:col-start-8">
                <form method="POST" action="#" class="flex">
                    @csrf
                    <input type="email" name="email" required placeholder="Your Email"
                           class="flex-1 min-w-0 bg-black border border-black text-white font-light text-sm px-5 outline-none placeholder:text-white/60 focus:border-brand transition-colors duration-200 h-[50px]">
                    <button type="submit" class="bg-brand text-white font-semibold text-sm px-8 h-[50px] shrink-0 hover:bg-brand-dark transition-colors duration-200">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- LINK COLUMNS --}}
    <section class="border-t border-white/10">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-14 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
            <nav aria-label="Information">
                <p class="font-medium text-sm mb-6">Information</p>
                <ul class="space-y-3 font-light text-sm text-white/90">
                    @foreach (['About Us', 'About Zip', 'Privacy Policy', 'Search', 'Terms', 'Orders and Returns', 'Contact Us'] as $label)
                        <li><a href="#" class="hover:text-brand transition-colors duration-200">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <nav aria-label="Shop">
                <p class="font-medium text-sm mb-6">Shop</p>
                <ul class="space-y-3 font-light text-sm text-white/90">
                    @foreach (['flagships', 'foldables', 'budget', 'gaming'] as $slug)
                        <li>
                            <a href="{{ route('products.index', ['category' => $slug]) }}" class="hover:text-brand transition-colors duration-200">
                                {{ ucfirst($slug) }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('products.index') }}" class="hover:text-brand transition-colors duration-200">All products</a>
                    </li>
                </ul>
            </nav>

            <div>
                <p class="font-medium text-sm mb-6">Address</p>
                <ul class="space-y-3 font-light text-sm text-white/90">
                    <li>No 131 Iwo Road<br>Opposite Item 7go, Iwo Road,<br>Ibadan</li>
                    <li>Phones: <a href="tel:+2347011111499" class="hover:text-brand transition-colors duration-200">+234 701 111 1499</a></li>
                    <li>We are open:<br>Monday-Saturday: 9:00 AM - 6:00 PM</li>
                    <li>E-mail: <a href="mailto:phonestation31@gmail.com" class="hover:text-brand transition-colors duration-200">phonestation31@gmail.com</a></li>
                </ul>
            </div>

            <nav aria-label="Account">
                <p class="font-medium text-sm mb-6">Account</p>
                <ul class="space-y-3 font-light text-sm text-white/90">
                    @foreach (['My Account', 'Orders', 'Wish List', 'Track Order', 'Returns'] as $label)
                        <li><a href="#" class="hover:text-brand transition-colors duration-200">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <nav aria-label="Support">
                <p class="font-medium text-sm mb-6">Support</p>
                <ul class="space-y-3 font-light text-sm text-white/90">
                    @foreach (['Help Centre', 'Shipping Info', 'Warranty', 'FAQ'] as $label)
                        <li><a href="#" class="hover:text-brand transition-colors duration-200">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </section>

    {{-- PAYMENT METHODS --}}
    <section class="border-t border-white/10">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-8 flex flex-wrap items-center gap-3">
            <span class="w-[34px] h-[22px] rounded-[3px] bg-[#D8E3F3] flex items-center justify-center">
                <span class="text-[8px] font-bold italic text-[#3362AB] tracking-tight">VISA</span>
            </span>
            <span class="w-[34px] h-[22px] rounded-[3px] bg-[#CCDEFF] flex items-center justify-center">
                <span class="text-[8px] font-bold italic text-[#003087] tracking-tight">Pay</span>
            </span>
            <span class="w-[34px] h-[22px] rounded-[3px] bg-[#CCEFFF] flex items-center justify-center gap-0.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#EB001B]"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#0099DF] opacity-80"></span>
            </span>
            <span class="w-[34px] h-[22px] rounded-[3px] bg-[#FCE0CF] flex items-center justify-center">
                <span class="text-[8px] font-bold text-[#F26E21] tracking-tight">Disc</span>
            </span>
            <span class="w-[34px] h-[22px] rounded-[3px] bg-[#D4DEF7] flex items-center justify-center">
                <span class="text-[7px] font-bold text-[#2557D6] tracking-tighter">AMEX</span>
            </span>
        </div>
    </section>

    {{-- COPYRIGHT --}}
    <section class="border-t border-white/10">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 h-12 flex items-center justify-between text-xs font-medium text-white">
            <p>Copyright © 2026 Phone Station</p>
            <p class="flex items-center gap-4">
                <a href="#" aria-label="Facebook" class="hover:opacity-70 transition-opacity">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127c-.292-.04-1.292-.126-2.455-.126-2.429 0-4.095 1.483-4.095 4.207v2.384H7.319v3.209h2.799v8.196h3.279z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Instagram" class="hover:opacity-70 transition-opacity">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
            </p>
        </div>
    </section>
</footer>
