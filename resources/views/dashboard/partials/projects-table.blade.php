<div class="bg-white overflow-hidden rounded-lg shadow-md mb-6">
    <div class="px-6 py-4 border-b">
        <h3 class="font-semibold text-lg text-gray-800">Software Projects</h3>
    </div>
    <div class="p-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr class="bg-gray-50 text-left">
                <th scope="col" class="px-4 py-2 text-sm text-gray-500 font-medium">
                    Name
                </th>
                <th scope="col" class="px-4 py-2 text-sm text-gray-500 font-medium">
                    Health
                </th>
                <th scope="col" class="px-4 py-2 text-sm text-gray-500 font-medium">
                    Outstanding Features
                </th>
                <th scope="col" class="px-4 py-2 text-sm text-gray-500 font-medium">
                    Next Release
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($projects as $project)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 text-sm">
                        <a href="{{ route('projects.show', ['project' => $project->id]) }}">{{ $project->name }}</a>
                    </td>
                    <td class="health px-4 py-2 text-sm text-gray-900">Running</td>
                    <td class="outstanding-features px-4 py-2 text-sm text-gray-900">{{ 123 }}</td>
                    <td class="next-release px-4 py-2 text-sm text-gray-900">{{ now() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
