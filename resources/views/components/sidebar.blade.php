<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"><img width="150" src="{{ asset('img/download.png') }}" alt=""></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">EX</a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ $type_menu === 'products' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i><span>Products</span></a>
            </li>
            <li class="{{ $type_menu === 'transactions' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions.index') }}">
                    <i class="fas fa-shopping-basket"></i><span>Transactions</span></a>
            </li>
    </aside>
</div>
