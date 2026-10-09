
/****** Object:  Table [dbo].[WEBENGINE_CREDITS_CONFIG]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_CREDITS_CONFIG](
	[config_id] [int] IDENTITY(1,1) NOT NULL,
	[config_title] [varchar](50) NOT NULL,
	[config_database] [varchar](50) NOT NULL,
	[config_table] [varchar](50) NOT NULL,
	[config_credits_col] [varchar](50) NOT NULL,
	[config_user_col] [varchar](50) NOT NULL,
	[config_user_col_id] [varchar](50) NOT NULL,
	[config_checkonline] [tinyint] NOT NULL,
	[config_display] [tinyint] NOT NULL,
 CONSTRAINT [PK_WEBENGINE_CREDITS_CONFIG] PRIMARY KEY CLUSTERED 
(
	[config_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_CREDITS_CONFIG] ON 

INSERT [dbo].[WEBENGINE_CREDITS_CONFIG] ([config_id], [config_title], [config_database], [config_table], [config_credits_col], [config_user_col], [config_user_col_id], [config_checkonline], [config_display]) VALUES (1, N'WCoinC', N'MuOnline', N'CashShopData', N'WCoinC', N'AccountID', N'username', 1, 0)
SET IDENTITY_INSERT [dbo].[WEBENGINE_CREDITS_CONFIG] OFF


