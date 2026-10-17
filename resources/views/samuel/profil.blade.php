<main class="py-12">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-lg mx-auto border border-[#e2e8f0]">
            <h2 class="text-2xl font-bold text-center text-[#172554] mb-6">Profil utilisateur</h2>
            
            <p class="text-base text-center text-[#4a4a4a] mb-8">
                Bienvenue sur votre espace personnel.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-[#6b7280] mb-2">Nom complet</h3>
                    <p class="text-lg font-medium text-[#172554]">Prénom Nom</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-[#6b7280] mb-2">Rôle</h3>
                    <p class="text-lg font-medium text-[#172554]">Samuel</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-[#6b7280] mb-2">Statut du compte</h3>
                    <p class="text-lg font-medium text-[#172554]">Actif</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-[#6b7280] mb-2">Dernière connexion</h3>
                    <p class="text-lg font-medium text-[#172554]">Aujourd'hui</p>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-[#e2e8f0]">
                <h3 class="text-sm font-medium text-[#6b7280] mb-3">Modifier le mot de passe</h3>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-2">Ancien mot de passe</label>
                        <input type="password" name="old_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-2">Nouveau mot de passe</label>
                        <input type="password" name="new_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#172554] to-[#3b82f6] text-white font-medium rounded-xl hover:opacity-90 transition-opacity">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </main>