<div class="bg-white">
    <div>
        <select id="filtersActive" name="filtersActive">
            <option value="all" {{ !request()->has('state') ? 'selected' : '' }}>All</option>
            <option value="1" {{ request()->query('state') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request()->query('state') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div>
        <select id="filtersEmpty" name="filtersEmpty">
            <option value="all" {{ !request()->has('empty') ? 'selected' : '' }}>All</option>
            <option value="1" {{ request()->query('empty') === '1' ? 'selected' : '' }}>Empty</option>
            <option value="0" {{ request()->query('empty') === '0' ? 'selected' : '' }}>Not Empty</option>
        </select>
    </div>
    <div>
        <select id="filtersPassed" name="filtersPassed">
            <option value="all" {{ !request()->has('passed') ? 'selected' : '' }}>All</option>
            <option value="1" {{ request()->query('passed') === '1' ? 'selected' : '' }}>Passed</option>
            <option value="0" {{ request()->query('passed') === '0' ? 'selected' : '' }}>Not Passed</option>
        </select>
    </div>
</div>
