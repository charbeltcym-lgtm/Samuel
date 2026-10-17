@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Cartes statistiques simplifiées -->
        <div class="lg:w-3/4 space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-lg text-[#6B7280]">Présences cette semaine</p>
                <p class="text-3xl font-bold text-[#15803D]">45</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-lg text-[#6B7280]">Samuels sous votre gestion</p>
                <p class="text-3xl font-bold text-[#172554]">28</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <p class="text-lg text-[#6B7280]">Absences cette semaine</p>
                <p class="text-3xl font-bold text-[#DC2626]">8</p>
            </div>
        </div>

        <!-- Actions rapides pour administrateur -->
        <div class="lg:w-1/4 space-y-3">
            <a href="/profil-admin" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#172554] hover:bg-[#F3F4F6] transition-colors">
                <svg class="w-5 h-5 text-[#172554]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 5v.01M12 12v.01M12 19v.01M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12l2-2m0 0l2-2 2-2m-4 4l2 2m-2-2l2 2m7-3.5a5.5 5.5 0 017.778 0M12 7a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
                Mon profil
            </a>
            
            <a href="/nouvelle-seance" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#172554] hover:bg-[#F3F4F6] transition-colors">
                <svg class="w-5 h-5 text-[#172554]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 5v.01M12 12v.01M12 19v.01M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12l2-2m0 0l2-2 2-2m-4 4l2 2m-2-2l2 2m7-3.5a5.5 5.5 0 017.778 0M12 7a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
                Nouvelle séance
            </a>
        </div>
    </div>

    <div class="lg:w-3/4 mt-6">
        @parent
    </div>
</div>