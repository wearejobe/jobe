<div class="side-bar-menu mt-4 pb-5">
    <div class="list-group dark-mode">
        <a href="{{ route('jobs') }}" class="list-group-item list-group-action no-round {{ Request::is('account/jobs') ? 'active' : '' }}">
            <span class="material-icons">dns</span>
            {{__('tr_jobs.my_jobs')}}
        </a>
        <a href="{{ route('notifications') }}" class="list-group-item list-group-action no-round {{ Request::is('account/notifications') ? 'active' : '' }}">
            <span class="material-icons">announcement</span>
            {{__('tr_general.notifications')}}
        </a>
        
        @if($usertype=='bj')
        
            <a href="{{ route('contracts') }}" class="list-group-item list-group-action no-round {{ Request::is('account/contracts') ? 'active' : '' }}">
                <span class="material-icons">contacts</span>
                {{__('tr_general.contracts')}}
            </a>
            <a href="{{ route('payment.receipts') }}" class="list-group-item list-group-action no-round {{ Request::is('account/receipts') ? 'active' : '' }}">
                <span class="material-icons">receipt</span>
                {{__('tr_general.invoices')}}
            </a>
            <a href="{{ route('payment.method') }}" class="list-group-item list-group-action no-round {{ Request::is('account/payment/method') ? 'active' : '' }}">
                <span class="material-icons">credit_card</span>
                {{__('tr_general.payment_method')}}
            </a>
            
            <a href="{{ route('new-job') }}" class="list-group-item list-group-action no-round">
                <span class="material-icons">add_circle_outline</span>
                {{__('tr_jobs.post_new_job')}}
            </a>
        @else

        @endif;
    </div>
</div>