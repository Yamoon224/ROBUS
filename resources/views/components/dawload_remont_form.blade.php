   <div class="mb-4 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 items-end">
        <div class="w-full sm:w-auto mt-3 mr-3">
            <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Date de début :
            </label>
            <input type="date" id="startDate" name="startDate"
                value="{{ request('startDate', \Carbon\Carbon::yesterday()->toDateString()) }}"
                class="form-control w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
        <div class="w-full sm:w-auto mt-3 mr-6">
            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Date de fin :
            </label>
            <input type="date" id="endDate" name="endDate"
                value="{{ request('endDate', \Carbon\Carbon::today()->toDateString()) }}"
                class="form-control w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
    </div>