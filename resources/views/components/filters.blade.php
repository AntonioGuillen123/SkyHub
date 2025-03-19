<div class="flex flex-col md:flex-row gap-4 md:gap-0 justify-evenly items-center font-bold">
    <div class="rounded-lg">
        <select id="filtersActive" name="filtersActive" class="rounded-lg w-36">
            <option value="all" {{ !request()->has('state') ? 'selected' : '' }}>📊 All</option>
            <option value="1" {{ request()->query('state') === '1' ? 'selected' : '' }}>🟢 Active</option>
            <option value="0" {{ request()->query('state') === '0' ? 'selected' : '' }}>🔴 Inactive</option>
        </select>
    </div>
    <div>
        <select id="filtersEmpty" name="filtersEmpty" class="rounded-lg w-36">
            <option value="all" {{ !request()->has('empty') ? 'selected' : '' }}>⚪ All</option>
            <option value="1" {{ request()->query('empty') === '1' ? 'selected' : '' }}>🕳️ Empty</option>
            <option value="0" {{ request()->query('empty') === '0' ? 'selected' : '' }}>📦 Not Empty</option>
        </select>
    </div>
    <div>
        <select id="filtersPassed" name="filtersPassed" class="rounded-lg  w-36">
            <option value="all" {{ !request()->has('passed') ? 'selected' : '' }}>⏳ All</option>
            <option value="1" {{ request()->query('passed') === '1' ? 'selected' : '' }}>🛩️ Passed</option>
            <option value="0" {{ request()->query('passed') === '0' ? 'selected' : '' }}>🛬 Not Passed</option>
        </select>
    </div>
</div>
