import { authStore } from "../store/auth";
import useUsers from "../composables/users";
import { before } from "lodash";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')
const AuthenticatedUserLayout = () => import('../layouts/AuthenticatedUser.vue')
const MainLayout = () => import('../layouts/MainLayout.vue');


async function requireLogin(to, from, next) {
	const auth = authStore();
	let isLogin = !!auth.authenticated;

	if (isLogin) {
		next()
	} else {
		next('/?openModal=login')
	}
}

async function requireValidation(to, from, next) {
	const { user, getUser } = useUsers();
	const auth = authStore();
	if (auth.user.id != null) {
		await getUser(auth.user.id);
		while (!user.value || !user.value.validation_status) {
			await new Promise((resolve) => setTimeout(resolve, 50));
		}

		if (user.value.validation_status == 'PENDING') {
			next('/?toast=pending')
		} else if (user.value.validation_status == "ACCEPTED") {
			next()
		} else if (user.value.validation_status == 'DENIED') {
			next('/?toast=denied')
		} else {
			next('/?toast=error')
		}
	} else {
		next('/?openModal=login')
	}
}

function hasAdmin(roles) {
	for (let rol of roles) {
		if (rol.name && rol.name.toLowerCase().includes('admin')) {
			return true;
		}
	}
	return false;
}
async function guest(to, from, next) {
	const auth = authStore()

	let isLogin = !!auth.authenticated;

	if (isLogin) {
		next('/')
	} else {
		next()
	}
}

async function requireAdmin(to, from, next) {

	const auth = authStore();
	let isLogin = !!auth.authenticated;
	let user = auth.user;

	if (isLogin) {
		if (hasAdmin(user.roles)) {
			next()
		} else {
			next('/')
		}
	} else {
		next('/?openModal=login')
	}
}

export default [
	{
		path: '/',
		// redirect: { name: 'login' },
		component: MainLayout,
		children: [

			{
				path: '/',
				name: 'home',
				component: () => import('../views/home/index.vue'),
			},
			{
				path: 'posts',
				name: 'public-posts.index',
				component: () => import('../views/posts/index.vue'),
			},
			{
				path: 'posts/:id',
				name: 'public-posts.details',
				component: () => import('../views/posts/details.vue'),
			},
			{
				path: 'category/:id',
				name: 'category-posts.index',
				component: () => import('../views/category/posts.vue'),
			},
			// {
			// 	path: 'login',
			// 	name: 'auth.login',
			// 	component: () => import('../views/login/Login.vue'),
			// 	beforeEnter: guest,
			// },
			// {
			// 	path: 'register',
			// 	name: 'auth.register',
			// 	component: () => import('../views/register/index.vue'),
			// 	beforeEnter: guest,
			// },
			// {
			// 	path: 'forgot-password',
			// 	name: 'auth.forgot-password',
			// 	component: () => import('../views/auth/passwords/Email.vue'),
			// 	beforeEnter: guest,
			// },
			// {
			// 	path: 'reset-password/:token',
			// 	name: 'auth.reset-password',
			// 	component: () => import('../views/auth/passwords/Reset.vue'),
			// 	beforeEnter: guest,
			// },
			{
				path: 'my-account',
				name: 'auth.my-account',
				component: () => import('../components/MyAccount.vue'),
				beforeEnter: requireLogin,
			},
			{
				path: 'game-history',
				name: 'auth.game-history',
				component: () => import('../components/GameHistory.vue'),
				beforeEnter: requireLogin,
			},
			{
				path: 'balance-history',
				name: 'auth.balance-history',
				component: () => import('../components/BalanceHistory.vue'),
				beforeEnter: requireLogin,
			},
			{
				path: 'achievements',
				name: 'auth.achievements',
				component: () => import('../components/Achievements.vue'),
				beforeEnter: requireLogin,
			},
			{
				path: 'games',
				name: 'auth.games',
				beforeEnter: [requireLogin, requireValidation],
				children: [
					{
						path: ':route',
						name: 'auth.gamerooms',
						component: () => import('../views/games/GameRoomsSelect.vue'),
						beforeEnter: [requireLogin, requireValidation],
					},
					{
						path: 'bingo/:id',
						name: 'auth.bingo.gameroom',
						component: () => import('../components/Bingo.vue'),
						beforeEnter: [requireLogin, requireValidation],
					}
				]
			},
			{
				path: 'verify-identity',
				name: 'auth.verify-identity',
				component: () => import('../components/VerifyIdentity.vue'),
				beforeEnter: requireLogin,
			},
		]
	},

	{
		path: '/app',
		component: AuthenticatedUserLayout,
		// redirect: {
		//     name: 'admin.index'
		// },
		name: 'app',
		beforeEnter: requireLogin,
		meta: { breadCrumb: 'Dashboard' }
	},


	{
		path: '/admin',
		component: AuthenticatedLayout,
		redirect: {
			name: 'admin.index'
		},
		beforeEnter: requireAdmin,
		meta: { breadCrumb: 'Dashboard' },
		children: [
			{
				name: 'admin.index',
				path: '',
				component: () => import('../views/admin/index.vue'),
				meta: { breadCrumb: 'Admin' }
			},
			{
				name: 'profile.index',
				path: 'profile',
				component: () => import('../views/admin/profile/index.vue'),
				meta: { breadCrumb: 'Profile' }
			},
			{
				name: 'categories',
				path: 'categories',
				meta: { breadCrumb: 'Categories' },
				children: [
					{
						name: 'categories.index',
						path: '',
						component: () => import('../views/admin/categories/Index.vue'),
						meta: { breadCrumb: 'View category' }
					},
					{
						name: 'categories.create',
						path: 'create',
						component: () => import('../views/admin/categories/Create.vue'),
						meta: {
							breadCrumb: 'Add new category',
							linked: false,
						}
					},
					{
						name: 'categories.edit',
						path: 'edit/:id',
						component: () => import('../views/admin/categories/Edit.vue'),
						meta: {
							breadCrumb: 'Edit category',
							linked: false,
						}
					}
				]
			},
			{
				name: 'permissions',
				path: 'permissions',
				meta: { breadCrumb: 'Permisos' },
				children: [
					{
						name: 'permissions.index',
						path: '',
						component: () => import('../views/admin/permissions/Index.vue'),
						meta: { breadCrumb: 'Permissions' }
					},
					{
						name: 'permissions.create',
						path: 'create',
						component: () => import('../views/admin/permissions/Create.vue'),
						meta: {
							breadCrumb: 'Create Permission',
							linked: false,
						}
					},
					{
						name: 'permissions.edit',
						path: 'edit/:id',
						component: () => import('../views/admin/permissions/Edit.vue'),
						meta: {
							breadCrumb: 'Permission Edit',
							linked: false,
						}
					}
				]
			},
			{
				name: 'users',
				path: 'users',
				meta: { breadCrumb: 'Users' },
				children: [
					{
						name: 'users.index',
						path: '',
						component: () => import('../views/admin/users/Index.vue'),
						meta: { breadCrumb: 'Usuarios' }
					},
					{
						name: 'users.create',
						path: 'create',
						component: () => import('../views/admin/users/Create.vue'),
						meta: {
							breadCrumb: 'Create User',
							linked: false
						}
					},
					{
						name: 'users.edit',
						path: 'edit/:id',
						component: () => import('../views/admin/users/Edit.vue'),
						meta: {
							breadCrumb: 'Edit User',
							linked: false
						}
					}
				]
			},
			{
				name: 'validations',
				path: 'validations',
				meta: { breadCrumb: 'Validations' },
				children: [
					{
						name: 'validations.index',
						path: '',
						component: () => import('../views/admin/validations/Index.vue'),
						meta: { breadCrumb: 'Validations' }
					},
					{
						name: 'validations.show',
						path: 'show/:id',
						component: () => import('../views/admin/validations/Show.vue'),
						meta: { breadCrumb: 'Check validation' }
					},
				]
			},
			{
				name: 'achievements',
				path: 'achievements',
				meta: { breadCrumb: 'Achievements' },
				children: [
					{
						name: 'achievements.index',
						path: '',
						component: () => import('../views/admin/achievements/Index.vue'),
						meta: { breadCrumb: 'Achievements' }
					},
					{
						name: 'achievements.create',
						path: 'create',
						component: () => import('../views/admin/achievements/Create.vue'),
						meta: { breadCrumb: 'Create achievement' }
					},
					{
						name: 'achievements.edit',
						path: 'achievements/edit/:id',
						component: () => import('../views/admin/achievements/Edit.vue'),
						meta: { breadCrumb: 'Edit achievement' }
					},
				]
			},
			{
				name: 'games',
				path: 'games',
				meta: { breadCrumb: 'Games' },
				children: [
					{
						name: 'games.index',
						path: '',
						component: () => import('../views/admin/games/Index.vue'),
						meta: { breadCrumb: 'Games' }
					},
					{
						name: 'games.create',
						path: 'create',
						component: () => import('../views/admin/games/Create.vue'),
						meta: { breadCrumb: 'Show game' }
					},
					{
						name: 'games.edit',
						path: 'edit/:id',
						component: () => import('../views/admin/games/Edit.vue'),
						meta: { breadCrumb: 'Edit game' }
					},
				]
			},
			{
				name: 'game-rooms',
				path: 'game-rooms',
				meta: { breadCrumb: 'Game rooms' },
				children: [
					{
						name: 'game-rooms.index',
						path: '',
						component: () => import('../views/admin/gameRooms/Index.vue'),
						meta: { breadCrumb: 'Game rooms' }
					},
					{
						name: 'game-rooms.create',
						path: 'create',
						component: () => import('../views/admin/gameRooms/Create.vue'),
						meta: { breadCrumb: 'Create game room' }
					},
					{
						name: 'game-rooms.edit',
						path: 'game-rooms/edit/:id',
						component: () => import('../views/admin/gameRooms/Edit.vue'),
						meta: { breadCrumb: 'Edit game room' }
					},
				]
			},

			//TODO Organizar rutas
			{
				name: 'roles.index',
				path: 'roles',
				component: () => import('../views/admin/roles/Index.vue'),
				meta: { breadCrumb: 'Roles' }
			},
			{
				name: 'roles.create',
				path: 'roles/create',
				component: () => import('../views/admin/roles/Create.vue'),
				meta: { breadCrumb: 'Create Role' }
			},
			{
				name: 'roles.edit',
				path: 'roles/edit/:id',
				component: () => import('../views/admin/roles/Edit.vue'),
				meta: { breadCrumb: 'Role Edit' }
			},

		]
	},
	{
		path: "/:pathMatch(.*)*",
		name: 'NotFound',
		component: () => import("../views/errors/404.vue"),
	},
];
