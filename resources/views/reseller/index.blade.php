@section('title') {{'Resellers'}} @endsection
@section('style')
    <x-modal-style></x-modal-style>
@endsection
@section('scripts')
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
@endsection
<x-app-layout>
    <x-slot name="header">
        {{ __('Reseller') }}
    </x-slot>

    <div class="container" id="app">
        <x-delete-confirm-modal></x-delete-confirm-modal>
        <div class="card shadow mt-3">
            <div class="card-body">
                <a href="{{ route('reseller.create') }}" class="btn btn-primary mb-3">Add a reseller</a>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <p class="my-1">{{ $message }}</p>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Accounts</th>
                            <th>Location</th>
                            <th>Provider</th>
                            <th>Disk</th>
                            <th>Price</th>
                            <th>Due</th>
                            <th>Had since</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($resellers))
                            @foreach($resellers as $row)
                                <tr>
                                    <td>{{ $row->main_domain }}</td>
                                    <td>{{ $row->reseller_type }}</td>
                                    <td>{{ $row->accounts }}</td>
                                    <td class="text-nowrap">{{ $row->location }}</td>
                                    <td class="text-nowrap">{{ $row->provider_name }}</td>
                                    <td>{{ $row->disk_as_gb }} <small>GB</small></td>
                                    <td>{{ $row->price }} {{$row->currency}} {{\App\Process::paymentTermIntToString($row->term)}}</td>
                                    <td>{{Carbon\Carbon::parse($row->next_due_date)->diffForHumans()}}</td>
                                    <td class="text-nowrap">{{ $row->owned_since }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('reseller.destroy', $row->service_id) }}" method="POST">
                                            <a href="{{ route('reseller.show', $row->service_id) }}"
                                               class="text-body mx-1"><i class="fas fa-eye" title="view"></i></a>
                                            <a href="{{ route('reseller.edit', $row->service_id) }}"
                                               class="text-body mx-1"><i class="fas fa-pen" title="edit"></i></a>
                                            <i class="fas fa-trash text-danger ms-3" @click="modalForm"
                                               id="btn-{{$row->main_domain}}" title="{{$row->service_id}}"></i>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-2 border text-red-500" colspan="3">No reseller hosting found.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        @if(Session::has('timer_version_footer') && Session::get('timer_version_footer') === 1)
            <p class="text-muted mt-4 text-end"><small>
                    Built on Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}
                    )</small>
            </p>
        @endif
    </div>
    <script>
        let app = new Vue({
            el: "#app",
            data: {
                "modal_hostname": '',
                "modal_id": '',
                "delete_form_action": '',
                showModal: false
            },
            methods: {
                modalForm(event) {
                    this.showModal = true;
                    this.modal_hostname = event.target.id.replace('btn-', '');
                    this.modal_id = event.target.title;
                    this.delete_form_action = 'reseller/' + this.modal_id;
                }
            }
        });
    </script>
</x-app-layout>
