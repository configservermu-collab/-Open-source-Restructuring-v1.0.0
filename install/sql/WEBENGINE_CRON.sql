
/****** Object:  Table [dbo].[WEBENGINE_CRON]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_CRON](
	[cron_id] [int] IDENTITY(1,1) NOT NULL,
	[cron_name] [varchar](100) NOT NULL,
	[cron_description] [varchar](max) NULL,
	[cron_file_run] [varchar](max) NOT NULL,
	[cron_run_time] [varchar](50) NOT NULL,
	[cron_last_run] [varchar](50) NULL,
	[cron_status] [int] NOT NULL,
	[cron_protected] [int] NOT NULL,
	[cron_file_md5] [varchar](50) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_CRON] ON 

INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (1, N'Levels Ranking', N'Scheduled task to update characters level ranking', N'levels_ranking.php', N'60', N'1753828683', 1, 0, N'a45392abc98bbac178ae266fbd54ec07')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (2, N'Resets Ranking', N'Scheduled task to update characters reset ranking', N'resets_ranking.php', N'60', N'1753828684', 1, 0, N'ce9b91a355f148ecdddff51374dae4fc')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (3, N'Killers Ranking', N'Scheduled task to update top killers ranking', N'killers_ranking.php', N'60', N'1753828684', 1, 0, N'cd5f670946012bade546fa0bab16e6fa')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (4, N'Master Level Ranking', N'Scheduled task to update characters master level ranking', N'masterlevel_ranking.php', N'60', N'1753828684', 1, 0, N'5fec3571b23042b5c641813903e14b88')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (5, N'Guilds Ranking', N'Scheduled task to update top guilds ranking', N'guilds_ranking.php', N'60', N'1753828684', 1, 0, N'b46be8a4156d9819a8126a7c9c6d157e')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (6, N'Grand Resets Ranking', N'Scheduled task to update characters grand reset ranking', N'grandresets_ranking.php', N'60', N'1753828684', 1, 0, N'cadefc7287a90810acc805ae9d7852fe')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (7, N'Online Ranking', N'Scheduled task to update top online ranking', N'online_ranking.php', N'60', N'1753828684', 1, 0, N'45bc50212f78f9cda9d799ff7f43af80')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (8, N'Gens Ranking', N'Scheduled task to update gens ranking', N'gens_ranking.php', N'60', N'1753828684', 1, 0, N'9fa5cdc62a6eb2c4001665e91b4f5d0f')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (9, N'Votes Ranking', N'Scheduled task to update vote rankings', N'votes_ranking.php', N'60', N'1753828684', 1, 0, N'4d9840af3662150bb1a5ccc02cf7f987')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (10, N'Castle Siege', N'Saves castle siege information in cache', N'castle_siege.php', N'60', N'1753828684', 1, 0, N'9264893bf9fcac8306f976c1ff177f22')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (11, N'Ban System', N'Scheduled task to lift temporal bans', N'temporal_bans.php', N'60', N'1753828684', 1, 0, N'1a6385b3147c1c12a9fa1254fffee5a1')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (12, N'Server Info', N'Scheduled task to update the sidebar statistics information', N'server_info.php', N'60', N'1753828684', 1, 0, N'546d14476b7f41c20357de2de50c2c22')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (13, N'Account Country', N'Scheduled task to detect the accounts country by their ip address', N'account_country.php', N'60', N'1753828684', 1, 0, N'95bd48dd13725dc25f85967c93473ad5')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (14, N'Character Country', N'Scheduled task to cache characters country', N'character_country.php', N'60', N'1753828684', 1, 0, N'85b98a48398a6ed66ce89ae6e20d6c4d')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (15, N'Online Characters', N'Scheduled task to cache online characters', N'online_characters.php', N'60', N'1753828684', 1, 0, N'daf9097280950d4da729938755559e1a')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (16, N'BloodCastle', NULL, N'bloodcastle_ranking.php', N'60', N'1753828684', 1, 0, N'6b48bef27ee58395b62e3f6514182996')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (17, N'ChaosCastle', NULL, N'chaoscastle_ranking.php', N'60', N'1753828684', 1, 0, N'9a0a46f9cea874d1ecc5c7c1c0f287a5')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (18, N'Devilsquare', NULL, N'devilsquare_ranking.php', N'60', N'1753828684', 1, 0, N'75c7ba349ed9bd833a383b38f5e64dde')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (19, N'Duelo', NULL, N'duel_ranking.php', N'60', N'1753828624', 1, 0, N'3ebbc38c8f5c2de18d9faff40fb22609')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (21, N'general', NULL, N'general_ranking.php', N'60', N'1753828624', 1, 0, N'd661e246495bca6cef2c104866812fbf')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (22, N'illusion', NULL, N'illusiontemple_ranking.php', N'60', N'1753828624', 1, 0, N'a60d09429a4b0aa244f7a47e13403526')
INSERT [dbo].[WEBENGINE_CRON] ([cron_id], [cron_name], [cron_description], [cron_file_run], [cron_run_time], [cron_last_run], [cron_status], [cron_protected], [cron_file_md5]) VALUES (24, N'Liga', NULL, N'liga_ranking.php', N'60', N'1753828624', 1, 0, N'ffcb1b76b16778eee63cf1ea19dc1859')
SET IDENTITY_INSERT [dbo].[WEBENGINE_CRON] OFF

