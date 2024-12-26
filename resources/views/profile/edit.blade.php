<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="flex items-center mb-4">
                        <input id="admin-checkbox" type="checkbox" value="1" class="role-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="admin-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">管理者</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input id="user-checkbox" type="checkbox" value="2" class="role-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="user-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">ユーザー</label>
                    </div>
                    <div class="flex items-center">
                        <input id="guest-checkbox" type="checkbox" value="3" class="role-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="guest-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">ゲスト</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        const userRole = {{ auth()->user()->role }};
        
        roleCheckboxes.forEach(checkbox => {
            checkbox.checked = checkbox.value == userRole;
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    roleCheckboxes.forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });
                    console.log('Updating role to:', this.value); // デバッグメッセージ
                    updateRole(this.value);
                }
            });
        });

        function updateRole(role) {
            fetch('{{ route('profile.updateRole') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ role: role })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Role updated successfully');
                  } else {
                      alert('Failed to update role');
                  }
              }).catch(error => {
                  console.error('Error updating role:', error); // デバッグメッセージ
              });
        }
    });
</script>
</x-app-layout>